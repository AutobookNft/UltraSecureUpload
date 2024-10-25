<?php
namespace Fabio\UltraSecureUpload\Services;

use Fabio\UltraSecureUpload\Contracts\SaveFilesInterface;
use Fabio\UltraSecureUpload\Exceptions\CustomException;
use Fabio\UltraSecureUpload\Helpers\GetDataOfHostingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Save files to all available storages
 *
 * @param string $fileToSave File path to save
 * @param string $tempRealPath Real path of the temporary file
 * @param string $destinationPathImage Path where to save the image
 * @return string "OK" if the file is saved successfully
 * @throws \Exception If the file cannot be saved
 */
class SaveFiles implements SaveFilesInterface
{
    public function save(string $fileToSave, string $tempRealPath, string $destinationPathImage): string
    {
        $channel = 'upload';
        $logParams = [
            'Class' => 'SaveFiles',
            'Method' => 'save',
        ];

        Log::channel($channel)->info(json_encode($logParams), [
            'Action' => 'Saving the file',
            'file_to_save' => $fileToSave,
            'temp_real_path' => $tempRealPath,
            'destination_path_image' => $destinationPathImage,
        ]);

        // Read the temporary file contents
        $contents = file_get_contents($tempRealPath);
        Log::channel($channel)->info(json_encode($logParams), [
            'Action' => 'File contents read successfully',
            'fileToSave' => $fileToSave
        ]);

        $path_external = [];
        $saved_on_hosts = [];

        try {
            // Get all active hosting services (including localhost)
            $activeHosts = GetDataOfHostingService::driverHostingService(); // List of active drivers, including 'public' (localhost)
        
            foreach ($activeHosts as $host) {
                try {
                    // Step 1: Save the file on each active host
                    $disk = Storage::disk($host);
                    $path = $disk->put($fileToSave, $contents, 'public') ?? '';
                    $path_external[$host] = $path;

                    Log::channel($channel)->info(json_encode($logParams), [
                        'Action' => 'File successfully saved on ' . $host,
                        'fileToSave' => $fileToSave
                    ]);

                    // Add the host to the list of successful saves
                    $saved_on_hosts[] = $host;
                    
                } catch (\Exception $e) {
                    Log::channel($channel)->error(json_encode($logParams), [
                        'Action' => 'Error saving on ' . $host,
                        'error' => $e->getMessage(),
                        'host' => $host
                    ]);

                    // Continue with the next host, but log the error
                    continue;
                }
            }
        
            // Ensure that at least one file has been saved successfully
            if (count($saved_on_hosts) === 0) {
                throw new CustomException('ALL_UPLOADS_FAILED');
            }

            return 'OK'; // All files saved successfully

        } catch (CustomException $e) {
            // Rethrow the exception after the rollback
            throw $e;

        } catch (\Exception $e) {
            Log::channel($channel)->error(json_encode($logParams), [
                'Action' => 'Unexpected error during the upload process',
                'error' => $e->getMessage()
            ]);

            // Rethrow the exception after the rollback
            throw $e;

        } finally {
            // Ensure rollback if any error occurs
            $this->rollbackFiles($saved_on_hosts, $path_external, $logParams);
        }
    }

    /**
     * Perform the rollback by deleting saved files from the hosts.
     *
     * @param array $saved_on_hosts The hosts where the file was saved successfully
     * @param array $path_external The paths of the saved files
     * @param array $logParams Logging parameters for contextual information
     * @return void
     */
    private function rollbackFiles(array $saved_on_hosts, array $path_external, array $logParams): void
    {
        $channel = 'upload';

        foreach ($saved_on_hosts as $host) {
            if (isset($path_external[$host]) && Storage::disk($host)->exists($path_external[$host])) {
                Storage::disk($host)->delete($path_external[$host]);
                Log::channel($channel)->error(json_encode($logParams), [
                    'Action' => 'Rollback: File deleted from ' . $host,
                    'path_external' => $path_external[$host]
                ]);
            }
        }
    }
}
