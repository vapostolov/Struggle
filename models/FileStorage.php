<?php

namespace Example\Models;

class FileStorage implements Storage {

  private $filename = null;

  public function __construct($filename) {
    $this->filename = $filename;
  }

  public function create($data) {
    throw new \Exception("Create method not implemented.");
  }

  public function read($id) {
    $file = new \SplFileObject($this->filename, "r");
    if ($file->flock(LOCK_EX)) {
      $file->seek($id);
      $data = $file->current();
      $file->flock(LOCK_UN);
    } else {
        return false;
    }

    return $this->mapStringDataToObject($data);
  }

  public function delete($id) {
    return $this->updateOrDelete($id);
  }

  public function update($id, $data) {
    return $this->updateOrDelete($id, $this->mapObjectToStringData($data));
  }

  private function updateOrDelete($id, $data = null) {
    /* Thanks to https://www.daniweb.com/web-development/php/threads/102279/deleting-a-line-from-a-file#post1353582 */

    /*
     * Create a new SplFileObject representation of the file
     * Open it for reading and writing and place the pointer at the beginning of the file
     * @see fopen for additional modes
     */
    $file = new \SplFileObject($this->filename, 'a+');
    /*
     * Set a bitmask of the flags to apply - Only relates to reading from file
     * In this case SplFileObject::DROP_NEW_LINE to remove new line charachters
     * and SplFileObject::SKIP_EMPTY to remove empty lines
     */
    $file->setFlags(7);
    /*
     * Lock the file so no other user can interfere with reading and writing while we work with it
     */
    $file->flock(LOCK_EX);
    /*
     * Create a SplTempFileObject
     * 0 indicates not to use memory to store the temp file.
     * This is probably slower than using memory, but for a large file it will be much more effective
     * than loading the entire file into memory
     * @see http://www.php.net/manual/en/spltempfileobject.construct.php for more details
     */
    $temp = new \SplTempFileObject(0);
    /*
     * Lock the temp file just in case
     */
    $temp->flock(LOCK_EX);
    /*
     * Iterate over each line of the file only loading one line into memory at any point in time
     */
    foreach ($file as $key => $line) {

      if ($key != $id) {
        /*
         * If this line does NOT match out delete write it to the temp file
         * Append a line ending to it
         */
        $temp->fwrite($line . PHP_EOL);
      } else if ($data !== null) {
            $temp->fwrite($data . PHP_EOL);
      }
    }
    /*
     * Truncate the existing file to 0
     */
    $file->ftruncate(0);
    /*
     * Write the temp file back to the existing file
     */
    foreach ($temp as $line) {

      /*
       * Iterate over temp file and put each line back into original file
       */
      $file->fwrite($line);
    }
    /*
     * Release the file locks
     */
    $temp->flock(LOCK_UN);
    $file->flock(LOCK_UN);

    return true;
  }

  private function mapStringDataToObject($data) {
    if ($data) {
        $data = str_getcsv($data);
        $user = new User();
        $user->name = $data[0];
        $user->phone = $data[1];
        $user->address = $data[2];
        return $user;
    }

    return null;
  }

  private function mapObjectToStringData($object) {
    return $object->name . "," . $object->phone . "," . $object->address;
  }

}

?>