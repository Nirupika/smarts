<?php
ob_start();
include '../../init.php';

if(!isset($_SESSION['logged_in']) || $_SESSION['role_id'] != 5){
    header("Location: login.php");
    exit();
}

$conn = dbConnect();

$id = $_GET['id'] ?? null;

if(!$id){
    die("Invalid Application ID");
}

$sql = "SELECT * FROM admission_applications
        WHERE id=:id AND parent_id=:parent_id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id' => $id,
    ':parent_id' => $_SESSION['user_id']
]);

$application = $stmt->fetch(PDO::FETCH_ASSOC);


if(!$application){
    die("Application not found");
}

$errors = [];
print_r($_POST);
if($_POST){

    extract($_POST);

    if(!empty($student_dob)){
        $dobDate = new DateTime($student_dob);
        $cutoffDate = new DateTime('2027-01-31');
        $age_as_at_2027 = $dobDate->diff($cutoffDate)->y;
    }else{
        $age_as_at_2027 = 0;
    }

    if(empty($category)){
        $errors['category']="Category is required";
    }

    if(empty($student_full_name)){
        $errors['student_full_name']="Student Full Name is required";
    }

    if(empty($student_initial_name)){
        $errors['student_initial_name']="Student Initial Name is required";
    }

    if(empty($student_gender)){
        $errors['student_gender']="Student Gender is required";
    }

    if(empty($student_dob)){
        $errors['student_dob']="Date of Birth is required";

    }
     if(!empty($student_dob)){
        $dob= new DateTime($student_dob);
        $today= new DateTime();
        $age= $today->diff($dob);
        
        if($age->y>=6){
            if($age->m>0 || $age->d>0){
                $errors['student_dob'] = "Student must be below 6 years old";
            }
        }
        if($age->y<5){
            $errors['student_dob'] = "Student must be above 5 years old";
        }
       echo $age=$age->y . " years, " . $age->m . " months, " . $age->d . " days";
    
    }

    if(empty($applicant_full_name)){
        $errors['applicant_full_name']="Applicant Full Name is required";
    }

    if(empty($applicant_nic)){
        $errors['applicant_nic']="NIC is required";
    }

    if(empty($mobile_no)){
        $errors['mobile_no']="Mobile Number is required";
    }

    if(empty($email)){
        $errors['email']="Email is required";
    }
 print_r($errors);

    if(empty($errors)){

        $sql = "UPDATE admission_applications SET

                category_id=:category,

                student_full_name=:student_full_name,
                student_initial_name=:student_initial_name,
                student_gender=:student_gender,
                student_religion=:student_religion,
                student_medium=:student_medium,
                student_dob=:student_dob,


                age=:age,

                applicant_full_name=:applicant_full_name,
                applicant_initial_name=:applicant_initial_name,
                applicant_gender=:applicant_gender,
                applicant_nic=:applicant_nic,
                permanent_address=:permanent_address,
                mobile_no=:mobile_no,
                whatsapp_no=:whatsapp_no,
                email=:email,
                administrative_district=:administrative_district,
                ds_division=:ds_division,
                gs_division=:gs_division,

                pd_no_1=:pd_no_1,
                serial_no_1=:serial_no_1,
                name_1=:name_1,

                pd_no_2=:pd_no_2,
                serial_no_2=:serial_no_2,
                name_2=:name_2,

                pd_no_3=:pd_no_3,
                serial_no_3=:serial_no_3,
                name_3=:name_3,

                pd_no_4=:pd_no_4,
                serial_no_4=:serial_no_4,
                name_4=:name_4,

                pd_no_5=:pd_no_5,
                serial_no_5=:serial_no_5,
                name_5=:name_5,

                nearby_school_1=:nearby_school_1,
                nearby_school_2=:nearby_school_2,
                nearby_school_3=:nearby_school_3

                WHERE id=:id
                AND parent_id=:parent_id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([

            ':category'=>$category,

            ':student_full_name'=>$student_full_name,
            ':student_initial_name'=>$student_initial_name,
            ':student_gender'=>$student_gender,
            ':student_religion'=>$student_religion,
            ':student_medium'=>$student_medium,
            ':student_dob'=>$student_dob,
            ':age'=>$age,

            ':applicant_full_name'=>$applicant_full_name,
            ':applicant_initial_name'=>$applicant_initial_name,
            ':applicant_gender'=>$applicant_gender,
            ':applicant_nic'=>$applicant_nic,
            ':permanent_address'=>$permanent_address,
            ':mobile_no'=>$mobile_no,
            ':whatsapp_no'=>$whatsapp_no,
            ':email'=>$email,
            ':administrative_district'=>$administrative_district,
            ':ds_division'=>$ds_division,
            ':gs_division'=>$gs_division,

            ':pd_no_1'=>$pd_no_1,
            ':serial_no_1'=>$serial_no_1,
            ':name_1'=>$name_1,

            ':pd_no_2'=>$pd_no_2,
            ':serial_no_2'=>$serial_no_2,
            ':name_2'=>$name_2,

            ':pd_no_3'=>$pd_no_3,
            ':serial_no_3'=>$serial_no_3,
            ':name_3'=>$name_3,

            ':pd_no_4'=>$pd_no_4,
            ':serial_no_4'=>$serial_no_4,
            ':name_4'=>$name_4,

            ':pd_no_5'=>$pd_no_5,
            ':serial_no_5'=>$serial_no_5,
            ':name_5'=>$name_5,

            ':nearby_school_1'=>$nearby_school_1,
            ':nearby_school_2'=>$nearby_school_2,
            ':nearby_school_3'=>$nearby_school_3,


    ':id' => $id,
    ':parent_id' => $_SESSION['user_id']
    
]);
    
           

        header("Location: ../dashboard.php? message=Application updated successfully");
        exit();
    }

}else{

    extract($application);
    
}
?>

<section class="contact-section section-padding">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <form method="post"
                    
                    class="custom-form contact-form p-4 shadow rounded bg-light">

                    <h2 class="text-center mb-4">
                       Edit Student Admission Application
                    </h2>

                    <!-- Category -->
                    <div class="row">

                        <div class="col-md-12 mb-4">

                        <?php

                        $conn=dbConnect();
                        $sql="SELECT * FROM admission_categories";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute();
                        $categories=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                            <label class="form-label">
                                Admission Category 
                            </label>
                            <select name="category"
                                class="form-control">

                                <option value="">
                                    Select Category
                                </option>

                                <?php foreach($categories as $cat){ ?>

                                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $category_id) ? 'selected' : '' ?>>
                                    <?= $cat['category'] ?>
                                </option>

                                <?php } ?>
                            </select>

                            <small class="text-danger">
                                <?= @$errors['category'] ?>

                        </div>

                    </div>

                    <!-- Student Details -->

                    <h4 class="mb-3 text-primary border-bottom pb-2">
                        Student Details
                    </h4>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Full Name</label>
                            <input type="text"
                                name="student_full_name"
                                class="form-control"
                                value="<?= @$student_full_name ?>">
                            <small class="text-danger">
                                <?= @$errors['student_full_name'] ?>
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Name with Initials</label>
                            <input type="text"
                                name="student_initial_name"
                                class="form-control"
                                value="<?= @$student_initial_name ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Gender</label>
                            <select name="student_gender"
                                class="form-control">

                                <option value="">
                                    Select Gender
                                </option>

                                <option value="Male" <?= ($student_gender == 'Male') ? 'selected' : '' ?>>
                                    Male
                                </option>

                                <option value="Female" <?= ($student_gender == 'Female') ? 'selected' : '' ?>>
                                    Female
                                </option>

                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Religion</label>
                            <input type="text"
                                name="student_religion"
                                class="form-control"
                                value="<?= @$student_religion ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Medium</label>
                            <select name="student_medium"
                                class="form-control">

                                <option value="">
                                    Select Medium
                                </option>

                                <option value="Sinhala" <?= ($student_medium == 'Sinhala') ? 'selected' : '' ?>>
                                    Sinhala
                                </option>

                                <option value="Tamil" <?= ($student_medium == 'Tamil') ? 'selected' : '' ?>>
                                    Tamil
                                </option>


                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Date of Birth</label>
                            <input type="date"
                                name="student_dob"
                                class="form-control"
                                min="2021-02-01" max="2022-01-31"
                            
                                
                                value="<?= @$student_dob ?>">
                                <small class="text-danger">
                                <?= @$errors['student_dob'] ?>
                                </small>
                        </div>

                        

                    </div>

                    <!-- Applicant Details -->

                    <h4 class="mb-3 mt-4 text-primary border-bottom pb-2">
                        Applicant Details
                    </h4>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Full Name</label>
                            <input type="text"
                                name="applicant_full_name"
                                class="form-control"
                                value="<?= @$applicant_full_name ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Name with Initials</label>
                            <input type="text"
                                name="applicant_initial_name"
                                class="form-control"
                                value="<?= @$applicant_initial_name ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Gender</label>
                            <select name="applicant_gender"
                                class="form-control">

                                <option value="">
                                    Select Gender
                                </option>

                                <option value="Male" <?= ($applicant_gender == 'Male') ? 'selected' : '' ?>>
                                    Male
                                </option>

                                <option value="Female" <?= ($applicant_gender == 'Female') ? 'selected' : '' ?>>
                                    Female
                                </option>

                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>NIC Number</label>
                            <input type="text"
                                name="applicant_nic"
                                class="form-control"
                                value="<?= @$applicant_nic ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Mobile Number</label>
                            <input type="text"
                                name="mobile_no"
                                class="form-control"
                                value="<?= @$mobile_no ?>">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Permanent Address</label>
                            <textarea name="permanent_address"
                                class="form-control"
                                rows="3"><?= @$permanent_address ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Whatsapp Number</label>
                            <input type="text"
                                name="whatsapp_no"
                                class="form-control"
                                value="<?= @$whatsapp_no ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email Address</label>
                            <input type="email"
                                name="email"
                                class="form-control"
                                value="<?= @$email ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Administrative District</label>

                         <?php

                        $conn=dbConnect();
                        $sql="SELECT * FROM admission_districts";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute();
                        $districts=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        
                            
                            <select name="administrative_district"
                                class="form-control">

                                <option value="">
                                    Select District
                                </option>

                                <?php foreach($districts as $dist){ ?>

                                <option value="<?= $dist['id'] ?>" <?= ($dist['id'] == $administrative_district) ? 'selected' : '' ?>>
                                    <?= $dist['districts'] ?>
                                </option>

                                <?php } ?>
                            </select>

                            <small class="text-danger">
                                <?= @$errors['administrative_district'] ?>
                            </small>

                            
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>DS-Division</label>

                         <?php

                        $conn=dbConnect();
                        $sql="SELECT * FROM admission_dsdivision";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute();
                        $ds_divisions=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        
                            
                            <select name="ds_division"
                                class="form-control">

                                <option value="">
                                    Select DS-Division
                                </option>

                                <?php foreach($ds_divisions as $ddivision){ ?>

                                <option value="<?= $ddivision['id'] ?>" <?= ($ddivision['id'] == $ds_division) ? 'selected' : '' ?>>
                                    <?= $ddivision['ds_division'] ?>
                                </option>

                                <?php } ?>
                            </select>

                            <small class="text-danger">
                                <?= @$errors['ds_division'] ?>
                            </small>

                            
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>GS Division Name </label>

                             <?php

                        $conn=dbConnect();
                        $sql="SELECT * FROM admission_gsdivision";
                        $stmt=$conn->prepare($sql);
                        $stmt->execute();
                        $gs_divisions=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        
                            
                            <select name="gs_division"
                                class="form-control">

                                <option value="">
                                    Select GS Division
                                </option>

                                <?php foreach($gs_divisions as $division){ ?>

                                <option value="<?= $division['id'] ?>" <?= ($division['id'] == $gs_division) ? 'selected' : '' ?>>
                                    <?= $division['gs_division'] ?> 
                                </option>

                                <?php } ?>
                            </select>

                            <small class="text-danger">
                                <?= @$errors['gs_division'] ?>
                            </small>

                        </div>

                    </div>

                    <!-- Electoral Registration -->

                    <h4 class="mb-3 mt-4 text-primary border-bottom pb-2">
                        Electoral Registration Details
                    </h4>

                    <table class="table table-bordered">

                        <thead class="table-light">

                            <tr>
                                <th>PD No</th>
                                <th>Serial No</th>
                                <th>Name</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php for($i=1;$i<=5;$i++){ ?>

                            <tr>

                                <td>
                                    <input type="text"
                                        name="pd_no_<?= $i ?>"
                                         value="<?= @$pd_no_[$i] ?>"
                                        class="form-control">
                                        
                                </td>

                                <td>
                                    <input type="text"
                                        name="serial_no_<?= $i ?>"
                                        class="form-control"
                                        value="<?= @$serial_no_[$i] ?>">
                                </td>

                                <td>
                                    <input type="text"
                                        name="name_<?= $i ?>"
                                        class="form-control"
                                        value="<?= @$name_[$i] ?>">
                                </td>

                            </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                    <!-- Nearby Schools -->

                    <h4 class="mb-3 mt-4 text-primary border-bottom pb-2">
                        Other Schools Near the Place of Residence
                    </h4>

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <input type="text"
                                name="nearby_school_1"
                                class="form-control"
                                placeholder="School 1"
                                value="<?= @$nearby_school_1 ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <input type="text"
                                name="nearby_school_2"
                                class="form-control"
                                placeholder="School 2"
                                value="<?= @$nearby_school_2 ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <input type="text"
                                name="nearby_school_3"
                                class="form-control"
                                placeholder="School 3"
                                value="<?= @$nearby_school_3 ?>">
                        </div>

                    </div>

                    <!-- Submit Button -->

                    <div class="mt-4">

                        <button type="submit"
                            class="btn btn-primary w-100">

                            Update Admission Application

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>

<?php
$content = ob_get_clean();
include '../layout.php';
?>