<?php


    class Employees
    {

        /** @var array|Employee[] */
        private array $employees;



        public function __construct()
        {
            $this->setEmployees( [] );
        }



        /**
         * @return array
         */
        public function getEmployees(): array
        {
            return $this->employees;
        }



        /**
         * @param array $employees
         */
        public function setEmployees( array $employees ): void
        {
            $this->employees = $employees;
        }



        /**
         * @param Employee $employee
         * @return void
         */
        public function attachEmployee( Employee $employee ): void
        {
            $this->employees[ $employee->getRowId() ] = $employee;
        }



        /**
         * @param int $idEmployee
         * @return void
         */
        public function removeEmployee( int $idEmployee ): void
        {
            if ( array_key_exists( $idEmployee, $this->getEmployees() ) )
            {
                unset( $this->employees[ $idEmployee ] );
            }
        }



        public function getFullNames(): array
        {
            $names = [];
            foreach ( $this->getEmployees() as $employee )
            {
                $names[] = $employee->getName() . ' ' . $employee->getSurname();
            }
            return $names;
        }

    }