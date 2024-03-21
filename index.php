<?php

    require 'model/Employee.php';
    require 'model/Employees.php';

    const TABLE_COLUMN_IDS = array(
        'name',
        'surname',
        'sex',
        'address',
        'city',
        'zip',
        'phone',
        'email',
        'position',
        'boss',
    );


    $file = file_get_contents( "adresar.csv", "r" );
    $rows = explode( "\n", $file );
    $headings = str_getcsv( $rows[ 0 ], ';' );

    unset( $rows[ 0 ] );
    unset( $rows[ count( $rows ) ] );

    $data = [];
    $i = 0;
    $employees = new Employees();

    foreach ( $rows as $row )
    {
        $columns = str_getcsv( $row, ';' );

        $rowData = array_combine( $headings, $columns );

        $data[] = $rowData;

        $employee = new Employee( $rowData[ 'Jméno' ], $rowData[ 'Příjmení' ], $rowData[ 'Pohlaví' ], $rowData[ 'Ulice' ], $rowData[ 'Obec' ], $rowData[ 'PSČ' ], $rowData[ 'Telefon' ], $rowData[ 'E-mail' ], $rowData[ 'Pozice' ], $rowData[ 'Nadřízený' ] );
        $employee->setRowId( $i );

        $employees->attachEmployee( $employee );

        $i++;
    }

    $names = $employees->getFullNames();

    //var_dump( $employees->getEmployees() );

    function trimWhiteSpaces( $data )
    {
        $formData = [];
        foreach ( $data as $key => $value )
        {
            $formData[ $key ] = trim( $value );
        }
        return $formData;
    }



    function validate( $data, $names )
    {
        $errors = [];

        if ( empty( $data[ 'name' ] ) )
        {
            $errors[ 'name' ][] = 'Jméno je povinné';
        }

        if ( empty( $data[ 'surname' ] ) )
        {
            $errors[ 'surname' ][] = 'Příjmení je povinné';
        }

        if ( empty( $data[ 'sex' ] ) )
        {
            $errors[ 'sex' ][] = 'Pohlaví je povinné';
        }

        if ( $data[ 'sex' ] != 'M' && $data[ 'sex' ] != 'Ž' )
        {
            $errors[ 'sex' ][] = 'Pohlaví musí být M nebo Ž';
        }

        if ( empty( $data[ 'city' ] ) )
        {
            $errors[ 'city' ][] = 'Obec je povinná';
        }

        if ( empty( $data[ 'zip' ] ) )
        {
            $errors[ 'zip' ][] = 'PSČ je povinné';
        }

        $data[ 'zip' ] = str_replace( ' ', '', $data[ 'zip' ] );

        if ( strlen( $data[ 'zip' ] ) != 5 )
        {
            $errors[ 'zip' ][] = 'PSČ musí mít 5 čísel';
        }

        if ( !is_numeric( $data[ 'zip' ] ) )
        {
            $errors[ 'zip' ][] = 'PSČ musí být číslo';
        }

        $data[ 'phone' ] = str_replace( ' ', '', $data[ 'phone' ] );
        $data[ 'phone' ] = str_replace( '+420', '', $data[ 'phone' ] );

        if ( $data[ 'phone' ] && !is_numeric( $data[ 'phone' ] ) )
        {
            $errors[ 'phone' ][] = 'Telefon musí být číslo';
        }

        if ( $data[ 'email' ] && !filter_var( $data[ 'email' ], FILTER_VALIDATE_EMAIL ) )
        {
            $errors[ 'email' ][] = 'E-mail musí být ve správném formátu';
        }

        if ( empty( $data[ 'position' ] ) )
        {
            $errors[ 'position' ][] = 'Pozice je povinná';
        }

        if ( isset($data ['position']) && $data['position'] === "dělník" && (!isset($data['boss']) || $data['boss'] === "" )) {
            $errors[ 'boss' ][] = 'Dělník musí mít nadřízeného';
        }

        if ( isset( $data[ 'boss' ] ) && $data[ 'boss' ] !== "" && !in_array( $data[ 'boss' ], $names ) )
        {
            $errors[ 'boss' ][] = 'Nadřízený musí být ze seznamu pracovníků';
        }

        return $errors;
    }



    if ( $_POST )
    {

        $data = trimWhiteSpaces( $_POST );
        $errors = validate( $data, $names );

        if ( empty( $errors ) )
        {
            $file = fopen( "adresar.csv", "a" );
            $row = array();

            foreach ( TABLE_COLUMN_IDS as $id )
            {
                $row[] = $_POST[ $id ];
            }

            fputcsv( $file, $row, ";" );
            fclose( $file );
            header( "Location: index.php" );
        }
    }

    if ( $_GET )
    {
        if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'load' && isset( $_GET[ 'id' ] ) && is_numeric( $_GET[ 'id' ] ) && array_key_exists( intval( $_GET[ 'id' ] ), $employees->getEmployees() ) )
        {
            var_dump( $employees->getEmployees()[ intval( $_GET[ 'id' ] ) ] );
            $employeeData = $employees->getEmployees()[ intval( $_GET[ 'id' ] ) ]->getAllDetails();
            foreach ( $employeeData as $key => $value )
            {
                $_REQUEST[ $key ] = $value;
            }
            $_REQUEST[ 'id' ] = $_GET[ 'id' ];
        }
    }
?>
<html>
<head>
    <title>Adresář</title>
</head>
<body>
<h1>Adresář</h1>
<table>
    <tr>
        <?php
            foreach ( $headings as $column )
            {
                echo '<th>' . $column . '</th>';
            }
        ?>

        <th colspan="2">Actions</th>
    </tr>
    <?php
        foreach ( $employees->getEmployees() as $employee )
        {
            echo '<tr><td>' . $employee->getName() . '</td><td>' . $employee->getSurname() . '</td><td>' . $employee->getSex() . '</td><td>' . $employee->getAddress() . '</td><td>' . $employee->getCity() . '</td><td>' . $employee->getZip() . '</td><td>' . $employee->getPhone() . '</td><td>' . $employee->getEmail() . '</td><td>' . $employee->getPosition() . '</td><td>' . $employee->getBoss() . '</td></tr>';
        }
    ?>
</table>
<form action="index.php" method="post" style="display: flex;flex-direction: column; max-width: 500px; gap: 20px">
    <input type="hidden" name="id" value="<?php if ( isset( $_REQUEST[ 'id' ] ) ) echo $_REQUEST[ 'id' ]; ?>">
    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="name">Jméno</label>
        <input type="text" name="name" value="<?php if ( isset( $_REQUEST[ 'name' ] ) ) echo $_REQUEST[ 'name' ]; ?>" required>
        <?php if ( !empty( $errors[ 'name' ] ) )
            foreach ( $errors[ 'name' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="surname">Příjmení</label>
        <input type="text" name="surname" value="<?php if ( isset( $_REQUEST[ 'surname' ] ) ) echo $_REQUEST[ 'surname' ]; ?>" required>
        <?php if ( !empty( $errors[ 'surname' ] ) )
            foreach ( $errors[ 'surname' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="sex">Pohlaví</label>
        <input type="text" name="sex" value="<?php if ( isset( $_REQUEST[ 'sex' ] ) ) echo $_REQUEST[ 'sex' ]; ?>" required>
        <?php if ( !empty( $errors[ 'sex' ] ) )
            foreach ( $errors[ 'sex' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="address">Ulice</label>
        <input type="text" name="address" value="<?php if ( isset( $_REQUEST[ 'address' ] ) ) echo $_REQUEST[ 'address' ]; ?>">
        <?php if ( !empty( $errors[ 'address' ] ) )
            foreach ( $errors[ 'address' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="city">Obec</label>
        <input type="text" name="city" value="<?php if ( isset( $_REQUEST[ 'city' ] ) ) echo $_REQUEST[ 'city' ]; ?>" required>
        <?php if ( !empty( $errors[ 'city' ] ) )
            foreach ( $errors[ 'city' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="zip">PSČ</label>
        <input type="text" name="zip" value="<?php if ( isset( $_REQUEST[ 'zip' ] ) ) echo $_REQUEST[ 'zip' ]; ?>" required>
        <?php if ( !empty( $errors[ 'zip' ] ) )
            foreach ( $errors[ 'zip' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="phone">Telefon</label>
        <input type="text" name="phone" value="<?php if ( isset( $_REQUEST[ 'phone' ] ) ) echo $_REQUEST[ 'phone' ]; ?>">
        <?php if ( !empty( $errors[ 'phone' ] ) )
            foreach ( $errors[ 'phone' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="email">E-mail</label>
        <input type="text" name="email" value="<?php if ( isset( $_REQUEST[ 'email' ] ) ) echo $_REQUEST[ 'email' ]; ?>">
        <?php if ( !empty( $errors[ 'email' ] ) )
            foreach ( $errors[ 'email' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="position">Pozice</label>
        <input type="text" name="position" value="<?php if ( isset( $_REQUEST[ 'position' ] ) ) echo $_REQUEST[ 'position' ]; ?>">
        <?php if ( !empty( $errors[ 'position' ] ) )
            foreach ( $errors[ 'position' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>

    <div style="display: flex;flex-direction: column; gap: 5px">
        <label for="boss">Nadřízený</label>
        <select name="boss" value="<?php if ( isset( $_REQUEST[ 'boss' ] ) ) echo $_REQUEST[ 'boss' ]; ?>">
            <option value=""></option>
            <?php foreach ( $names as $name )
            {
                echo '<option value="' . $name . '"';
                if ( isset( $_REQUEST[ 'boss' ] ) && $_REQUEST[ 'boss' ] == $name )
                {
                    echo ' selected';
                }
                echo '>' . $name . '</option>';
            } ?>
        </select>
        <?php if ( !empty( $errors[ 'boss' ] ) )
            foreach ( $errors[ 'boss' ] as $error )
            {
                echo '<div style="color: red">' . $error . '</div>';
            } ?>
    </div>


    <input type="submit" value="Přidat">
</form>
</body>
</html>
