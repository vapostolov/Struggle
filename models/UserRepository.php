<?php

    namespace Example\Models;

    class UserRepository {
        private $storage;
        private $numberOfIORetries = 5;

        public function __construct($storage) {
            $this->storage = $storage;
        }

        public function getUserById($id) {
            $user = false;
            $retries = 0;
            while ($user === false && $retries < $this->numberOfIORetries) {
                $user = $this->storage->read($id);
                $retries++;
            }
            return $user;
        }

        public function createUser($data) {
            $success = false;
            $retries = 0;
            while ($success === false && $retries < $this->numberOfIORetries) {
                $success = $this->storage->create($this->mapJsonDataToUser($data));
                $retries++;
            }
            return $success;
        }

        public function deleteUserById($id) {
            return $this->storage->delete($id);
        }

        public function updateUserById($id, $data) {
            return $this->storage->update($id, $this->mapJsonDataToUser($data));
        }

        private function mapJsonDataToUser($data) {
            $data = json_decode($data);
            $user = new User();
            $user->name = $data->name;
            $user->phone = $data->phone;
            $user->address = $data->address;
            return $user;
        }

    }
?>