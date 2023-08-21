<?php


// It just removes any contents of a Directory but not the target Directory itself!
// Its really nice if you want to clean a BackupDirectory or Log.
function deleteContent($path)
{
    try {
        $iterator = new DirectoryIterator($path);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDot()) continue;
            if ($fileinfo->isDir()) {
                if (deleteContent($fileinfo->getPathname()))
                    @rmdir($fileinfo->getPathname());
            }
            if ($fileinfo->isFile()) {
                @unlink($fileinfo->getPathname());
            }
        }
    } catch (Exception $e) {
        // write log
        return false;
    }
    return true;
}

?>
