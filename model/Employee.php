<?php

    class Employee
    {

        /** @var int */
        private int $rowId;

        /** @var string */
        private string $name;

        /** @var string */
        private string $surname;

        /** @var string */
        private string $sex;

        /** @var string */
        private string $address;

        /** @var string */
        private string $city;

        /** @var string */
        private string $zip;

        /** @var string */
        private string $phone;

        /** @var string */
        private string $email;

        /** @var string */
        private string $position;

        /** @var string */
        private string $boss;



        /**
         * @param string $name
         * @param string $surname
         * @param string $sex
         * @param string $address
         * @param string $city
         * @param string $zip
         * @param string $phone
         * @param string $email
         * @param string $position
         * @param string $boss
         */
        public function __construct( string $name, string $surname, string $sex, string $address, string $city, string $zip, string $phone, string $email, string $position, string $boss )
        {
            $this->setName( $name );
            $this->setSurname( $surname );
            $this->setSex( $sex );
            $this->setAddress( $address );
            $this->setCity( $city );
            $this->setZip( $zip );
            $this->setPhone( $phone );
            $this->setEmail( $email );
            $this->setPosition( $position );
            $this->setBoss( $boss );
        }



        /**
         * @return int
         */
        public function getRowId(): int
        {
            return $this->rowId;
        }



        /**
         * @param int $rowId
         */
        public function setRowId( int $rowId ): void
        {
            $this->rowId = $rowId;
        }



        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }



        /**
         * @param string $name
         */
        public function setName( string $name ): void
        {
            $this->name = $name;
        }



        /**
         * @return string
         */
        public function getSurname(): string
        {
            return $this->surname;
        }



        /**
         * @param string $surname
         */
        public function setSurname( string $surname ): void
        {
            $this->surname = $surname;
        }



        /**
         * @return string
         */
        public function getSex(): string
        {
            return $this->sex;
        }



        /**
         * @param string $sex
         */
        public function setSex( string $sex ): void
        {
            $this->sex = $sex;
        }



        /**
         * @return string
         */
        public function getAddress(): string
        {
            return $this->address;
        }



        /**
         * @param string $address
         */
        public function setAddress( string $address ): void
        {
            $this->address = $address;
        }



        /**
         * @return string
         */
        public function getCity(): string
        {
            return $this->city;
        }



        /**
         * @param string $city
         */
        public function setCity( string $city ): void
        {
            $this->city = $city;
        }



        /**
         * @return string
         */
        public function getZip(): string
        {
            return $this->zip;
        }



        /**
         * @param string $zip
         */
        public function setZip( string $zip ): void
        {
            $this->zip = $zip;
        }



        /**
         * @return string
         */
        public function getPhone(): string
        {
            return $this->phone;
        }



        /**
         * @param string $phone
         */
        public function setPhone( string $phone ): void
        {
            $this->phone = $phone;
        }



        /**
         * @return string
         */
        public function getEmail(): string
        {
            return $this->email;
        }



        /**
         * @param string $email
         */
        public function setEmail( string $email ): void
        {
            $this->email = $email;
        }



        /**
         * @return string
         */
        public function getPosition(): string
        {
            return $this->position;
        }



        /**
         * @param string $position
         */
        public function setPosition( string $position ): void
        {
            $this->position = $position;
        }



        /**
         * @return string
         */
        public function getBoss(): string
        {
            return $this->boss;
        }



        /**
         * @param string $boss
         */
        public function setBoss( string $boss ): void
        {
            $this->boss = $boss;
        }



        public function getAllDetails(): array
        {
            return [
                'name' => $this->getName(),
                'surname' => $this->getSurname(),
                'sex' => $this->getSex(),
                'address' => $this->getAddress(),
                'city' => $this->getCity(),
                'zip' => $this->getZip(),
                'phone' => $this->getPhone(),
                'email' => $this->getEmail(),
                'position' => $this->getPosition(),
                'boss' => $this->getBoss()
            ];
        }



    }