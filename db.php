<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "practical_z";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getAllProducts()
{
    global $conn;
    $result = $conn->query("SELECT * FROM products");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getProductById($productId)
{
    global $conn;
    $result = $conn->query("SELECT * FROM products WHERE id = $productId");
    return $result->fetch_assoc();
}

function searchProducts($query)
{
    global $conn;
    $result = $conn->query("SELECT * FROM products WHERE name LIKE '%$query%'");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllCategories()
{
    global $conn;
    $result = $conn->query("SELECT * FROM categories");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getProductsByCategory($category_id)
{
    global $conn;
    $result = $conn->query("SELECT * FROM products WHERE category_id = $category_id");
    return $result->fetch_all(MYSQLI_ASSOC);
}

?>