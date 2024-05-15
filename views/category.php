<?php 
    function sortList() {
        if(isset($_GET["sort"])) {
            switch ($_GET["sort"]) {
                case 1:
                case 2 :
                    return  " order by product.sold DESC";
                   
                case 3 :
                    return  " order by product.create_date DESC" ;
                
                case 4 : 
                    return  " order by product.price_final DESC";
                  
                case 5:
                    return  " order by product.price_final ASC";
                   
            }
        }
    return "" ;
    }

        $page = $_GET['page'];
        $row_count = 0;
        $query = "SELECT * FROM category";
        $result = mysqli_query($connect,$query);
        if(isset($_SESSION["tukhoa"])) {
            $tukhoa = $_SESSION["tukhoa"] ;
            $link = "?page=$page&key=$tukhoa";
        }
        // check get sản phẩm theo danh mục
        if(isset($_GET["id"])) {
            $dm_id = $_GET["id"];
            $link =  "?page=$page&id=$dm_id";
            $query1 = "SELECT name from category where id = ? ";
            $query = "WITH RankedTable AS (
                SELECT product.*, product_image.path_url, brand.name as brand_name,
                    ROW_NUMBER() OVER (PARTITION BY product.id ORDER BY product_image.id ASC) AS rn
                FROM product 
                INNER JOIN product_category ON product.id = product_category.product_id 
                INNER JOIN category ON category.id = product_category.category_id 
                INNER JOIN product_image ON product.id = product_image.product_id 
                INNER JOIN brand ON brand.id = product.brand_id  
                WHERE category.id = ?";
            $sort = sortList();
            $query.=$sort;
            $query.=" )  SELECT * FROM RankedTable WHERE rn = 1 ";
            $statement = mysqli_prepare($connect, $query); 
            mysqli_stmt_bind_param($statement, "i", $dm_id);
            mysqli_stmt_execute($statement);
            $result_search = mysqli_stmt_get_result($statement);
            $row_count = mysqli_num_rows($result_search);
            $statement1 = mysqli_prepare($connect, $query1);
            mysqli_stmt_bind_param($statement1, "i", $dm_id);
            mysqli_stmt_execute($statement1);
            $result_category_search = mysqli_stmt_get_result($statement1);
            $result_category_search = mysqli_fetch_array($result_category_search);
        } 
    // tìm kiếm
    if(isset($_POST["keyword"])) {
        $tukhoa = $_POST["keyword"];
        $_SESSION["tukhoa"] = $tukhoa;
        $link = "?page=$page&key=$tukhoa";
        $sql_pro = "WITH RankedTable AS (
            SELECT product.*, product_image.path_url ,
                   ROW_NUMBER() OVER (PARTITION BY product.id ORDER BY product_image.id ASC) AS rn
            FROM product 
            inner JOIN product_image ON product.id = product_image.product_id 
            WHERE product.name LIKE '%".$tukhoa."%'
        )
        SELECT *
        FROM RankedTable
        WHERE rn = 1 ";
        $result_search  = mysqli_query($connect,$sql_pro);
        $brand = "SELECT brand.id, brand.name,count(product.brand_id) as brand_num FROM brand left join product on brand.id = product.brand_id and product.name LIKE '%".$tukhoa."%' group by brand.id,brand.name";
        $result_brand = mysqli_query($connect,$brand);
        $row_count = mysqli_num_rows($result_search);
     }
     // lọc kết quả tìm kiếm
    if(isset($_GET["key"]) ) {
        $sql_pro = "WITH RankedTable AS (
            SELECT product.*, product_image.path_url ,brand.name as brand_name,
                   ROW_NUMBER() OVER (PARTITION BY product.id ORDER BY product_image.id ASC) AS rn
            FROM product 
            inner JOIN product_image ON product.id = product_image.product_id 
            inner join brand on brand.id = product.brand_id
            WHERE product.name LIKE '%".$tukhoa."%' ";
            $sort = sortList();
            $sql_pro.=$sort;
            $sql_pro.=" )  SELECT * FROM RankedTable WHERE rn = 1 ";
        $result_search  = mysqli_query($connect,$sql_pro);
        $row_count = mysqli_num_rows($result_search);
        $brands = "SELECT brand.id, brand.name,count(product.brand_id) as brand_num FROM brand left join product on brand.id = product.brand_id and product.name LIKE '%".$tukhoa."%' group by brand.id,brand.name";
        $result_brand = mysqli_query($connect,$brands);
        $row_count = mysqli_num_rows($result_search);
     }
     if(isset($_GET["brand-id"])) {
        $brand_id = $_GET["brand-id"];
        $link = "?page=$page&key=$tukhoa&brand-id=$brand_id";
        $brand = "SELECT name from brand where id = ? ";
        $statement_brand = mysqli_prepare($connect, $brand);
        mysqli_stmt_bind_param($statement_brand, "i",$brand_id );
        mysqli_stmt_execute($statement_brand);
        $result_brand_search = mysqli_stmt_get_result($statement_brand);
        $result_brand_search = mysqli_fetch_array($result_brand_search);
        $sql_pro = "WITH RankedTable AS (
            SELECT product.*, product_image.path_url ,brand.name as brand_name,
                   ROW_NUMBER() OVER (PARTITION BY product.id ORDER BY product_image.id ASC) AS rn
                   FROM product 
        right JOIN product_image ON product.id = product_image.product_id 
        right JOIN brand ON brand.id = product.brand_id 
        WHERE product.name LIKE CONCAT('%', ?, '%')  
        AND brand.id = ?";
        $sort = sortList();
        $sql_pro.=$sort;
        $sql_pro.=" )  SELECT * FROM RankedTable WHERE rn = 1 ";
        $statement3 = mysqli_prepare($connect, $sql_pro); // Sử dụng biến $sql_pro
        mysqli_stmt_bind_param($statement3, "si", $tukhoa, $_GET["brand-id"]); 
        mysqli_stmt_execute($statement3);
        $result_search = mysqli_stmt_get_result($statement3);
        $row_count = mysqli_num_rows($result_search);
        $brands = "SELECT brand.id, brand.name,count(product.brand_id) as brand_num FROM brand left join product on brand.id = product.brand_id and product.name LIKE '%".$tukhoa."%' group by brand.id,brand.name";
        $result_brand = mysqli_query($connect,$brands);
        $row_count = mysqli_num_rows($result_search);
     }
    ?>

<div class="container">
    <div class="layout-page-products-list mb-5">
        <form action="https://cocolux.com/danh-muc/son-moi-lips-i.98" id="form_filter" method="post">
            <div class="layout-main mb-5 bg-white">
                <div class="layout-filter">
                    <div class="layout-title text-uppercase fw-bold">
                        <i class="fa-solid fa-filter"></i>
                        <span>Bộ lọc tìm kiếm</span>
                    </div>
                    <div class="filter-list">
                        <div class="filter-group">
                            <div class="filter-group-title">Danh mục</div>
                            <div class="filter-group-items">
                                <?php 
                                    while($row=mysqli_fetch_array($result)) {
                                        ?>
                                <a href="?page=danh-muc&id=<?php echo $row["id"]?>"
                                    class="filter-item "><?php echo $row["name"] ?></a>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <?php 
                        if (isset($_POST["keyword"]) || isset($_GET["brand-id"]) || isset($_GET["key"])) {
                            echo '<div class="filter-group">
                            <div class="filter-group-title">Thương hiệu</div>
                            <div class="filter-group-items product_attribute_thuong_hieu">';
                                while ($row3 = mysqli_fetch_array($result_brand)) {
                                echo '<a href="'.$link.'&brand-id='.$row3["id"]. '" class="filter-item">
                                <input type="hidden" name="product_attribute_thuong_hieu" value="">
                                ' . $row3["name"] . '
                                <span>(' . $row3["brand_num"] . ')</span>
                                </a>';
                                }
                            echo '   </div>
                            </div>';
                            }
                    ?>

                    </div>
                </div>
                <div class="layout-list">
                    <div class="layout-title text-uppercase fw-bold">
                        <h1><?php echo isset($_GET["id"]) ?$result_category_search["name"] : " " ?>
                            (<?php echo $row_count   ?> KẾT QUẢ)</h1>
                    </div>

                    <div class="layout-card">
                        <div class="card-group">
                            <div class="card-title">Lọc theo</div>
                            <div class="card-items">
                                <span class="card-item card-filter active">
                                    <?php echo isset($_GET["id"]) ? "Danh mục: " : "Từ khóa: " ?>

                                    <?php echo isset($_GET["id"]) ? $result_category_search["name"] : $tukhoa ?>
                                </span>
                                <?php echo isset($_GET["brand-id"]) ? '<span class="card-item card-filter active"> Thương hiệu: '.($result_brand_search["name"])."</span>":"" ?>
                            </div>
                        </div>
                        <div class="card-group">
                            <div class="card-title">Sắp xếp theo</div>
                            <div class="card-items">
                                <a class="card-item card-sort " href="<?php echo $link ?>&sort=1" data-name="sort"
                                    data-value="1">

                                    Nổi bật
                                </a>
                                <a class="card-item card-sort " href="<?php echo $link ?>&sort=2" data-name="sort"
                                    data-value="2">

                                    Bán chạy
                                </a>
                                <a class="card-item card-sort " href="<?php echo $link ?>&sort=3" data-name="sort"
                                    data-value="3">

                                    Hàng mới
                                </a>
                                <a class="card-item card-sort " href="<?php echo $link ?>&sort=4" data-name="sort"
                                    data-value="4">

                                    Giá cao tới thấp
                                </a>
                                <a class="card-item card-sort " href="<?php echo $link ?>&sort=5" data-name="sort"
                                    data-value="5">

                                    Giá thấp tới cao
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="layout-list-items mb-4">
                        <?php 
                          if(isset($_GET["id"]) || isset($_POST["keyword"]) || isset($_GET["key"]) || $_GET["brand-id"]) {
                                while($row2=mysqli_fetch_array($result_search)) {
                                ?>
                        <a href="https://cocolux.com/chi-ke-vien-moi-romand-lip-matte-pencil-02-dovey-pink-i.8809625247287"
                            class="product-template">
                            <?php echo !empty($row2["price_original"]) ? '<div class="product-discount"><span class="pe-1">' . floor(100 * (($row2["price_original"] - $row2["price_final"])) / ($row2["price_original"])) . '%</span></div>' : ''; ?>


                            <div class="product-thumbnail ">
                                <img src="<?php echo $row2["path_url"] ?>"
                                    alt="Chì Kẻ Viền Môi Romand Lip Matte Pencil - 02 Dovey Pink" class="img-fluid">
                            </div>
                            <div class="product-price">
                                <div class="public-price">
                                    <?php echo $row2["price_final"] ?>

                                </div>
                                <div class="origin-price">
                                    <?php echo !empty($row2["price_original"]) ? $row2["price_original"] : "" ?>
                                </div>
                            </div>
                            <div class="product-brand">
                                <?php echo empty($row2["brand_name"]) ? "" :  $row2["brand_name"] ?>
                            </div>
                            <div class="product-title">
                                <?php echo $row2["name"]; ?>
                            </div>
                        </a>
                        <?php
                            }
                            ?>
                        <?php
                        } ?>


                    </div>

                    <nav class="mb-5" aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">

                            <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                                <span class="page-link" aria-hidden="true">‹</span>
                            </li>

                            <li class="page-item active"><a class="page-link active">1</a></li>
                            <li class="page-item"><a class="page-link"
                                    href="https://cocolux.com/danh-muc/son-moi-lips-i.98?page=2">2</a></li>
                            <li class="page-item"><a class="page-link"
                                    href="https://cocolux.com/danh-muc/son-moi-lips-i.98?page=3">3</a></li>
                            <li class="page-item"><a class="page-link">...</a></li>
                            <li class="page-item hidden-xs"><a class="page-link"
                                    href="https://cocolux.com/danh-muc/son-moi-lips-i.98?page=32">32</a></li>


                            <li class="page-item">
                                <a class="page-link" href="https://cocolux.com/danh-muc/son-moi-lips-i.98?page=2"
                                    rel="next" aria-label="Next »">›</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </form>


    </div>
</div>