<?php
session_start();
require 'db.php';

if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $products = searchProducts($search_query);
} else {
    $products = getAllProducts();
}

$categories = getAllCategories();
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $products = getProductsByCategory($category_id);
}

include './layouts/header.php';
?>

<div class="mb-3">
    <form class="relative mb-4 flex w-full flex-wrap items-stretch" method="get" action="index.php">
        <input type="search" name="search"
            class="relative m-0 -mr-0.5 block min-w-0 flex-auto rounded-l border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-[#FBFBFB] focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none"
            placeholder="Поиск" aria-label="Search">
        <button
            class="relative z-[2] flex items-center rounded-r bg-neutral-600 px-6 py-2.5 text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:bg-neutral-500 hover:shadow-lg"
            type="submit" id="button-addon1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                <path fill-rule="evenodd"
                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </form>
</div>


<ul class="my-5">
    <li>
        <a href="index.php">
            Все
        </a>
    </li>
    <?php
    foreach ($categories as $category) {
        ?>
        <li>
            <a href="index.php?category_id=<?= $category['id'] ?>">
                <?= $category['name'] ?>
            </a>
        </li>
    <?php } ?>
</ul>

<div class="flex gap-5 justify-between my-5">
    <?php
    foreach ($products as $product) {
        ?>

        <div class="block rounded-lg bg-white shadow-lg dark:bg-neutral-700 text-left">
            <div class="p-6 w-64 h-64 flex flex-col justify-between">
                <h5 class="mb-2 text-xl font-bold tracking-wide text-neutral-800 dark:text-neutral-50">
                    <?= $product['name'] ?>
                </h5>
                <p class="mb-2 text-base text-neutral-500 dark:text-neutral-300">
                    <?= $product['description'] ?> <br>
                    <?= number_format($product['price'], 0, ',', ' ') ?> руб.
                </p>
                <form method="post" action="index.php">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input
                        class="w-full mt-3 inline-block rounded bg-blue-500 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-blue-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-blue-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-blue-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        type="submit" name="add_to_cart" value="Добавить в корзину">
                </form>
            </div>
        </div>

        <?php
    } ?>
</div>
<?php
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $_SESSION['cart'][] = $product_id;

    function getProductPriceById($productId) {
        global $conn;
        $result = $conn->query("SELECT price FROM products WHERE id = $productId");
        $row = $result->fetch_assoc();
        return $row['price'];
    }

    $product_price = getProductPriceById($product_id);

    $sql = "INSERT INTO cart (product_id, product_price) VALUES ($product_id, $product_price)";
    $conn->query($sql);

    echo "Товар с ID $product_id добавлен в корзину.";
    exit();
}

include './layouts/footer.php';
?>