<?php 
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
    function sortList() {
        if(isset($_GET["sort"])) {
            switch ($_GET["sort"]) {
                case 1:
                case 2 :
                    return  " order by sold DESC";
                   
                case 3 :
                    return  " order by create_date DESC" ;
                
                case 4 : 
                    return  " order by price_final DESC";
                  
                case 5:
                    return  " order by price_final ASC";
                   
            }
        }
    return "" ;
    }
        $currentPage = isset($_GET["current-page"]) ? $_GET["current-page"] : 1 ;
        $limit = 1 ;
        $offset = ($currentPage - 1) * $limit ;
        $page = $_GET['page'];
        $row_count = 0;
        $query = "SELECT * FROM category";
        $result = mysqli_query($connect,$query);
        if(isset($_SESSION["tukhoa"])) {
            $tukhoa = $_SESSION["tukhoa"] ;
            $link = "?page=$page&key=$tukhoa";
            $linkPage = "?page=$page&key=$tukhoa". ($currentPage > 1 ? "&current-page=".$currentPage : "");
        }
        $total_pages = 0;
        // check get sản phẩm theo danh mục
        if(isset($_GET["id"])) {
            $dm_id = $_GET["id"];
            $link =  "?page=$page&id=$dm_id" ;
            $linkPage =  "?page=$page&id=$dm_id". ($currentPage > 1 ? "&current-page=".$currentPage : "") ;
            $query1 = "SELECT name from category where id = ? ";
            $total_pages_sql = "SELECT COUNT(*)
            FROM product 
            INNER JOIN product_category ON product.id = product_category.product_id 
            INNER JOIN category ON category.id = product_category.category_id 
            WHERE category.id = ?
            group by product.id" ;
            $statement_page = mysqli_prepare($connect, $total_pages_sql); 
            mysqli_stmt_bind_param($statement_page, "i", $dm_id);
            mysqli_stmt_execute($statement_page);
            $result_total = mysqli_stmt_get_result($statement_page);
            $total_rows = mysqli_num_rows($result_total);
            $total_pages = ceil($total_rows / $limit) ;
            $query = "SELECT * FROM (
                SELECT product.*, product_image.path_url, brand.name AS brand_name
                FROM product 
                INNER JOIN product_category ON product.id = product_category.product_id 
                INNER JOIN category ON category.id = product_category.category_id 
                INNER JOIN product_image ON product.id = product_image.product_id 
                INNER JOIN brand ON brand.id = product.brand_id  
                WHERE category.id = ?
                GROUP BY product.id
                LIMIT ? OFFSET  ?
            ) AS sub_query ";
            $sort = sortList();
            $query.=$sort;
            $statement = mysqli_prepare($connect, $query); 
            mysqli_stmt_bind_param($statement, "iii", $dm_id,$limit,$offset);
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
        $link = "?page=$page&key=$tukhoa" ;
        $linkPage =  "?page=$page&key=$tukhoa". ($currentPage > 1 ? "&current-page=".$currentPage : "") ;
        
      }
     // lọc kết quả tìm kiếm
    if(isset($_GET["key"]) ) {
        $total_pages_sql = "SELECT COUNT(*)
        FROM product 
        WHERE name LIKE '%".$tukhoa."%' group by id " ;
        $result_total = mysqli_query($connect,$total_pages_sql);
        $total_rows = mysqli_num_rows($result_total);
        $total_pages = ceil($total_rows / $limit) ;
        $sql_pro = "SELECT * FROM (
        SELECT product.*, product_image.path_url ,brand.name as brand_name
        FROM product 
        inner JOIN product_image ON product.id = product_image.product_id 
        inner join brand on brand.id = product.brand_id
        WHERE product.name LIKE CONCAT('%', ?, '%') group by product.id limit ? offset ? ) as sub_query ";
        $sort = sortList();
        $sql_pro.=$sort;
        $statement_sql_pro = mysqli_prepare($connect, $sql_pro);
        mysqli_stmt_bind_param($statement_sql_pro, "sii",$tukhoa, $limit,$offset);
        mysqli_stmt_execute($statement_sql_pro);
        $result_search  = mysqli_stmt_get_result($statement_sql_pro);
        $row_count = mysqli_num_rows($result_search);
        $brands = "SELECT brand.id, brand.name,count(product.brand_id) as brand_num FROM brand left join product on brand.id = product.brand_id and product.name LIKE '%".$tukhoa."%' group by brand.id,brand.name";
        $result_brand = mysqli_query($connect,$brands);
     }
     // tìm theo thương hiệu
     if(isset($_GET["brand-id"])) {
        $brand_id = $_GET["brand-id"];
        $link = "?page=$page&key=$tukhoa&brand-id=$brand_id" ;
        $linkPage = "?page=$page&key=$tukhoa&brand-id=$brand_id". ($currentPage > 1 ? "&current-page=".$currentPage : "") ;
        $total_pages_sql = "SELECT COUNT(*)
        FROM product inner join brand on brand.id = product.brand_id
        WHERE product.name LIKE '%".$tukhoa."%' and brand.id = ?  group by product.id " ;
        $statement_page = mysqli_prepare($connect, $total_pages_sql);
        mysqli_stmt_bind_param($statement_page, "i",$brand_id );
        mysqli_stmt_execute($statement_page);
        $result_total = mysqli_stmt_get_result($statement_page);
        $total_rows = mysqli_num_rows($result_total);
        $total_pages = ceil($total_rows / $limit) ;
        $brand = "SELECT name from brand where id = ? ";
        $statement_brand = mysqli_prepare($connect, $brand);
        mysqli_stmt_bind_param($statement_brand, "i",$brand_id );
        mysqli_stmt_execute($statement_brand);
        $result_brand_search = mysqli_stmt_get_result($statement_brand);
        $result_brand_search = mysqli_fetch_array($result_brand_search);
        $sql_pro = "SELECT * FROM (
            SELECT product.*, product_image.path_url ,brand.name as brand_name
                   FROM product 
        right JOIN product_image ON product.id = product_image.product_id 
        right JOIN brand ON brand.id = product.brand_id 
        WHERE product.name LIKE CONCAT('%', ?, '%')  
        AND brand.id = ? group by product.id limit ? offset ? ) as sub_query";
        $sort = sortList();
        $sql_pro.=$sort;
        $statement3 = mysqli_prepare($connect, $sql_pro); // Sử dụng biến $sql_pro
        mysqli_stmt_bind_param($statement3, "siii", $tukhoa, $_GET["brand-id"],$limit,$offset); 
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
                                echo '<a href="?page='.$page.'&key='.$tukhoa.'&brand-id='.$row3["id"] .'" class="filter-item">
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
                            (<?php echo $total_rows  ?> KẾT QUẢ)</h1>
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
                                <a class="card-item card-sort <?php echo isset($_GET["sort"]) && $_GET["sort"] == "1" ? "active": "" ?>"
                                    href="<?php echo $linkPage ?>&sort=1">

                                    Nổi bật
                                </a>
                                <a class="card-item card-sort <?php echo isset($_GET["sort"]) && $_GET["sort"] == "2" ? "active": "" ?> "
                                    href="<?php echo $linkPage ?>&sort=2">

                                    Bán chạy
                                </a>
                                <a class="card-item card-sort <?php echo isset($_GET["sort"]) && $_GET["sort"] == "3" ? "active": "" ?> "
                                    href="<?php echo $linkPage ?>&sort=3">

                                    Hàng mới
                                </a>
                                <a class="card-item card-sort <?php echo isset($_GET["sort"]) && $_GET["sort"] == "4" ? "active": "" ?> "
                                    href="<?php echo $linkPage ?>&sort=4">

                                    Giá cao tới thấp
                                </a>
                                <a class="card-item card-sort <?php echo isset($_GET["sort"]) && $_GET["sort"] == "5" ? "active": "" ?> "
                                    href="<?php echo $linkPage ?>&sort=5">

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
                        <a href="?page=san-pham&id=<?=$row2["id"] ?>" class="product-template">
                            <?php echo $row2["price_original"] > $row2["price_final"] ? '<div class="product-discount"><span class="pe-1">' . floor(100 * (($row2["price_original"] - $row2["price_final"])) / ($row2["price_original"])) . '%</span></div>' : ''; ?>
                            <div class="product-thumbnail ">
                                <img src="<?php echo $row2["path_url"] ?>"
                                    alt="Chì Kẻ Viền Môi Romand Lip Matte Pencil - 02 Dovey Pink" class="img-fluid">
                            </div>
                            <div class="product-price">
                                <div class="public-price">
                                    <?= number_format($row2['price_final'], 0, ',', '.') . ' VNĐ' ?>

                                </div>
                                <div class="origin-price">
                                    <?php echo $row2["price_original"] > $row2["price_final"] ? number_format($row2['price_original'], 0, ',', '.') . ' VNĐ' : "" ?>
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

                            <li class="page-item " aria-disabled="true" aria-label="« Previous">
                                <a class="page-link <?= $currentPage <= 1 ? 'disabled':'' ?> "
                                    href="<?php echo $link . ($currentPage > 2 ? "&current-page=".( $currentPage-1) : "" )?>"
                                    aria-hidden="true">‹</a>
                            </li>
                            <?php 
                        for ($i = 1; $i <= $total_pages; $i++) {
                           
                                // Display all pages if total pages are 4 or less
                                echo '<li class="page-item ' . ($currentPage == $i ? "active" : "") . '">
                                        <a href="' . ($link . ($i > 1 ? "&current-page=" . $i : "")) . '" 
                                        class="page-link ' . ($currentPage == $i ? "active" : "") . '">' . $i . '</a>
                                      </li>';
                            
                        }
                        
                        ?>
                            <li class="page-item">
                                <a class="page-link <?= $currentPage >= $total_pages ? 'disabled':'' ?>"
                                    href="<?php echo $link . ("&current-page=".( $currentPage+1)) ?>" rel="next"
                                    aria-label="Next »">›</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </form>


    </div>
</div>