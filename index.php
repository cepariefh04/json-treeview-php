<?php

//Koneksi Ke Database
$host = "localhost";
$username = "root";
$password = "";
$database = 'db_laba_rugi';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi Gagal " . mysqli_connect_error());
}

$query = "SELECT * FROM a WHERE parent = 0";
$result = mysqli_query($conn, $query);

$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $item = array(
        "Parent" => $row['ID'],
        "Nama_Akun" => $row['Nama_Akun'],
        "tipe" => $row['tipe'],
        "ID_Akun" => $row['ID_Akun'],
        "Saldo_Awal" => $row['Saldo_Awal'],
        "Saldo_Akhir" => $row['Saldo_Akhir'],
        "details" => array()
    );

    // fetch data untuk detail parent (children)
    $queryChildren = "SELECT * FROM a WHERE Parent = " . $row['ID'];
    $resultQueryChildren = mysqli_query($conn, $queryChildren);

    if ($resultQueryChildren) {
        while ($childrens = mysqli_fetch_assoc($resultQueryChildren)) {
            $detail = array(
                "ID" => $childrens['ID'],
                "Nama_Akun" => $childrens['Nama_Akun'],
                "tipe" => $childrens['tipe'],
                "ID_Akun" => $childrens['ID_Akun'],
                "Saldo_Awal" => $childrens['Saldo_Awal'],
                "Saldo_Akhir" => $childrens['Saldo_Akhir']
            );
            $item['details'][] = $detail;
        }
    }
    $data[] = $item;
}
mysqli_close($conn);

$jsonData = json_encode(array("data" => $data), JSON_PRETTY_PRINT);
header('Content-Type:application/json');
echo $jsonData;
