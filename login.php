<?php
ob_start();
include '../init.php';

$errors = [];

if($_POST){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // VALIDATION
    if(empty($email)){
        $errors['email'] = "Email should not be blank";
    }

    if(empty($password)){
        $errors['password'] = "Password should not be blank";
    }

    if(empty($errors)){

        $conn = dbConnect();

        $sql = "SELECT * FROM users
                WHERE email = :email
                AND role_id = 5";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])){

            // Sessions
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['logged_in'] = true;

            // Redirect
            header("Location: dashboard.php");
            exit();

        } else {

            $errors['login'] = "Invalid email or password";

        }
    }
}
?>

<section class="contact-section section-padding">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-5">

                <form method="post"
                      class="custom-form contact-form p-4 shadow rounded bg-light">

                    <h2 class="text-center mb-4">
                        Parent Login
                    </h2>

                    <?php if(!empty($errors['login'])){ ?>
                        <div class="alert alert-danger">
                            <?= $errors['login'] ?>
                        </div>
                    <?php } ?>

                    <!-- Email -->
                    <div class="mb-3">

                        <label>Email Address</label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="<?= @$email ?>">

                        <small class="text-danger">
                            <?= @$errors['email'] ?>
                        </small>

                    </div>

                    <!-- Password -->
                    <div class="mb-4">

                        <label>Password</label>

                        <input type="password"
                               name="password"
                               class="form-control">

                        <small class="text-danger">
                            <?= @$errors['password'] ?>
                        </small>

                    </div>

                      <!-- Submit -->
                    <button type="submit"
                            class="btn btn-primary w-100">

                        Login

                    </button>

                    <div class="text-center mt-3">

                        <a href="parent/register.php">
                            Create Parent Account
                        </a>

                    </div>

        

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>

<?php
$content = ob_get_clean();
include 'layout.php';
?>