<?php
include_once("header_b.php");
include_once("search_circular_service.php");

$departments = get_department($conn);
// print_r($_SESSION['user']);
$user_code = $_SESSION['user'];


$verified_user_for_create_circular = ['000339', 'SYSTEM'];


?>

<section class="search-container container">
    <div class="heading">
        <h1>Internal Circular</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="search-form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="department" class="form-label">DEPARTMENT</label>
                            <select class="form-select" aria-label="Default select example" id="department"
                                name="department">
                                <option selected value="">SELECT</option>
                                <?php 
                                    for($depIndx= 0; $depIndx < sizeof($departments['DEPTCODE']); $depIndx++){
                                        echo '<option value="'.$departments['DEPTCODE'][$depIndx].'">'. $departments['DEPTNAME'][$depIndx] .'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="input-from-date" class="form-label">PUBLISHED DATE</label>
                            <input type="date" class="form-control" id="input-from-date" name="published_date">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="circular-no" class="form-label">CIRCULAR NO</label>
                            <input type="text" class="form-control" id="circular-no" name="circular_no">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 d-grid">
                            <button class="btn btn-primary submit-btn d-flex align-items-center justify-content-center"
                                type='submit'>SEARCH<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-search ms-1 submit-btn" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                        <div class="col-md-6 d-grid">
                            <button type="button"
                                class="btn btn-success btn-block d-flex align-items-center justify-content-center"
                                onclick="location.href='home.php'">Home <span><svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor" class="bi bi-house-fill ms-1"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                                        <path fill-rule="evenodd"
                                            d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
                                    </svg>
                                </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


<!-- Check if the user is verified for creating circular -->
<?php  
    if (in_array($user_code, $verified_user_for_create_circular)){
        echo '
        <section class="create-circular container">
            <div class="create-circular-container d-flex flex-row-reverse">
                <a href="new_circular.php" class= "btn btn-primary px-3 py-1 mw-100 my-2">+ UPLOAD</a>
            </div>
        </section>        
        ';
    }
    ?>


<!-- POST method form -->
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $department = $_POST['department'];
        $published_date = str_ireplace("-", "", $_POST['published_date']);
        $circular_no = $_POST['circular_no'];
        $circulars = get_circular($conn, $department, $published_date, $circular_no);
        // print_r($circulars);
        // print_r($_POST);
        $length = sizeof($circulars['ID']);
        ?>
<section class="search-result container">
    <?php 
        if ($length) {
            echo "<p class='result-success'>FOUND {$length} RESULT</p>";
            echo '<table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th scope="col">SL</th>
                    <th scope="col">TITLE</th>
                    <th scope="col">PUBLISHED DATE</th>
                    <th scope="col">DEPARTMENT</th>
                    <th scope="col">CIRCULAR NO</th>
                    <th scope="col">FILE</th>
                </tr>
            </thead>
            <tbody>';
            echo "<hr>";

        }else {
            echo "<p class='result-error'> NO RESULTS FOUND ! </p>";
        }
    ?>

    <?php
    for($inx = 0; $inx < $length; $inx++) {


?>
    <tr class="flex align-items-center">
        <th scope="row"><?= $inx + 1; ?></th>
        <td><?= $circulars['TITLE'][$inx]?></td>
        <td><?= $circulars['PUBLISHED_DATE'][$inx]?></td>
        <td><?= $circulars['DEPTNAME'][$inx]?></td>
        <td><?= $circulars['CIRCULAR_NO'][$inx]?></td>
        <td><a target="_blank" rel="noreferrer noopener"
                href="assets/circulars/<?= $circulars['FILE_NAME'][$inx]?>"><svg xmlns="http://www.w3.org/2000/svg"
                 width="22" height="22" fill="#2a9d8f" class="bi bi-file-pdf-fill mw-100" viewBox="0 0 16 16">
                    <path
                        d="M5.523 10.424c.14-.082.293-.162.459-.238a7.878 7.878 0 0 1-.45.606c-.28.337-.498.516-.635.572a.266.266 0 0 1-.035.012.282.282 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548zm2.455-1.647c-.119.025-.237.05-.356.078a21.035 21.035 0 0 0 .5-1.05 11.96 11.96 0 0 0 .51.858c-.217.032-.436.07-.654.114zm2.525.939a3.888 3.888 0 0 1-.435-.41c.228.005.434.022.612.054.317.057.466.147.518.209a.095.095 0 0 1 .026.064.436.436 0 0 1-.06.2.307.307 0 0 1-.094.124.107.107 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256zM8.278 4.97c-.04.244-.108.524-.2.829a4.86 4.86 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.517.517 0 0 1 .145-.04c.013.03.028.092.032.198.005.122-.007.277-.038.465z" />
                    <path fill-rule="evenodd"
                        d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm.165 11.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.64 11.64 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.856.856 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.844.844 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.76 5.76 0 0 0-1.335-.05 10.954 10.954 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.238 1.238 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a19.707 19.707 0 0 1-1.062 2.227 7.662 7.662 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103z" />
                </svg></a>
        </td>
    </tr>
    <?php }?>
    </tbody>
    </table>
</section>

<!-- GET Method form -->
<?php
    }else{
        $circulars = get_circular($conn);
        // print_r($circulars);
        $length = sizeof($circulars['ID']);
        ?>
<section class="search-result container">
    <hr>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th scope="col">SL</th>
                <th scope="col">TITLE</th>
                <th scope="col">PUBLISHED DATE</th>
                <th scope="col">DEPARTMENT</th>
                <th scope="col">CIRCULAR NO</th>
                <th scope="col">FILE</th>
            </tr>
        </thead>
        <tbody>
            <?php
    for($inx = 0; $inx < $length; $inx++) {


?>
            <tr>
                <th scope="row"><?= $inx + 1?></th>
                <td><?= $circulars['TITLE'][$inx]?></td>
                <td><?= $circulars['PUBLISHED_DATE'][$inx]?></td>
                <td><?= $circulars['DEPTNAME'][$inx]?></td>
                <td><?= $circulars['CIRCULAR_NO'][$inx]?></td>
                <td><a target="_blank" rel="noreferrer noopener"
                        href="assets/circulars/<?= $circulars['FILE_NAME'][$inx]?>"><svg
                            xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#2a9d8f"
                            class="bi bi-file-pdf-fill mw-100" viewBox="0 0 16 16">
                            <path
                                d="M5.523 10.424c.14-.082.293-.162.459-.238a7.878 7.878 0 0 1-.45.606c-.28.337-.498.516-.635.572a.266.266 0 0 1-.035.012.282.282 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548zm2.455-1.647c-.119.025-.237.05-.356.078a21.035 21.035 0 0 0 .5-1.05 11.96 11.96 0 0 0 .51.858c-.217.032-.436.07-.654.114zm2.525.939a3.888 3.888 0 0 1-.435-.41c.228.005.434.022.612.054.317.057.466.147.518.209a.095.095 0 0 1 .026.064.436.436 0 0 1-.06.2.307.307 0 0 1-.094.124.107.107 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256zM8.278 4.97c-.04.244-.108.524-.2.829a4.86 4.86 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.517.517 0 0 1 .145-.04c.013.03.028.092.032.198.005.122-.007.277-.038.465z" />
                            <path fill-rule="evenodd"
                                d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm.165 11.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.64 11.64 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.856.856 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.844.844 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.76 5.76 0 0 0-1.335-.05 10.954 10.954 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.238 1.238 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a19.707 19.707 0 0 1-1.062 2.227 7.662 7.662 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103z" />
                        </svg></a>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</section>

<!-- Footer section -->
<?php
}

include_once('footer.php');
?>