<!-- Header Section -->
<?php
include_once('header_b.php');
include_once('search_circular_service.php');
$departments = get_department($conn);
// print_r($_SESSION);
?>
<!-- Body Section -->
<section class="create-form-header">
</section>
<section class="create-form container">
    <div class="card">
        <div class="card-header">
            <h5 class="mx-0 my-0">⚙ Upload New Circular</h5>
        </div>
        <div class="card-body">
            <form action= "upload.php" method="POST" enctype="multipart/form-data">
                <div class="form">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="publised_date" class="form-label">Published Date</label>
                            <input type="date" class="form-control" id="published_date" placeholder="" name="published_date" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="circular-no" class="form-label">Circular No</label>
                            <input type="text" class="form-control" id="circular-no" placeholder="Enter the circular no" name="circular_no" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div class="form-group">
                            <label for="title" class="form-label">Circular Title</label>
                            <input type="text" class="form-control" id="title" placeholder="Enter title of the circular" name="title" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-select" aria-label="Default select example" id="department"
                                name="department" required>
                                <option selected value="">SELECT</option>
                                <?php 
                                    for($depIndx= 0; $depIndx < sizeof($departments['DEPTCODE']); $depIndx++){
                                        echo '<option value="'.$departments['DEPTCODE'][$depIndx].'">'. $departments['DEPTNAME'][$depIndx] .'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="formFile" class="form-label">Attachment</label>
                            <input class="form-control" type="file" id="formFile" name="file" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 d-grid">
                        <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">UPLOAD
                                    <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-upload ms-1" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                        </svg>
                                    </span>

                        </button>
                    </div>
                    <div class="col-md-4 d-grid">
                        <a type="button" href= "#" class="btn btn-success d-flex align-items-center justify-content-center" onclick="history.back()">BACK
                    
                                    <span>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-arrow-left-circle mx-1 d-inline-grid justify-content-center align-items-center' viewBox='0 0 16 16'>
                                        <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                                    </svg>
                                    </span>
                        </a>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button type="reset" class="btn btn-danger d-flex align-items-center justify-content-center">RESET
                                    <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-x-octagon-fill ms-1" viewBox="0 0 16 16">
                                        <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                                    </svg>
                                    </span>

                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


<script>
    const publishedDate = document.querySelector('#published_date');


    const setDate = () => {
        let today = new Date();
        let sanitezedTodayDate = `${today.getFullYear()}-${today.getMonth()+1}-${today.getDate()}`
        publishedDate.value = sanitezedTodayDate;
    }
</script>

<!-- Footer Section -->
<?php include_once('footer.php'); ?>