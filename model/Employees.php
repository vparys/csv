<?php


    class Employees
    {

        /** @var array|Employee[] */
        private array $employees;

        /** @var array */
        private array $headings;



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
         * @return array
         */
        public function getHeadings(): array
        {
            return $this->headings;
        }



        /**
         * @param array $headings
         * @return void
         */
        public function setHeadings( array $headings ): void
        {
            $this->headings = $headings;
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



        /**
         * @return array
         */
        public function getFullNames(): array
        {
            $names = [];
            foreach ( $this->getEmployees() as $employee )
            {
                $names[] = $employee->getName() . ' ' . $employee->getSurname();
            }
            return $names;
        }



        /**
         * @return array
         */
        public function getFullTableInArrayForm(): array
        {
            $table = [];
            $table[] = $this->getHeadings();
            foreach ( $this->getEmployees() as $employee )
            {
                $table[] = $employee->toArray();
            }
            return $table;
        }



        /**
         * @param string $heading
         * @return void
         */
        public function sortByHeading( string $heading ): void
        {
            $columnPosition = array_search( $heading, $this->getHeadings() );
            if ( $columnPosition === false )
            {
                return;
            }
            uasort( $this->employees, function ( $a, $b ) use ( $columnPosition ) {
                return array_values( $a->toArray() )[ $columnPosition ] <=> array_values( $b->toArray() )[ $columnPosition ];
            } );
        }


    }