<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftEng2</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-white">
    <?php include('../includes/nav.php'); ?> 

    <header class="text-dark py-5 mt-4 mt-md-5 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="d-none d-md-block display-5 fw-bold text-center">Freelance Your Way<br>Online or On-Site in Angeles City</h1>
                    <h2 class="d-md-none display-5 fw-bold">Freelance Your Way<br>Online or On-Site in Angeles City</h2>
                    <p class="d-md-none">Discover opportunities waiting for you, whether you are an Employer or an aspiring Employee. Your skills and needs meet here. We make it easy to find trusted freelancers or flexible work that fits your lifestyle. No complicated steps. Just local opportunities, real people, and a platform built to help Angelenos.</p>
                    <p class="d-none d-md-block text-center">Discover opportunities waiting for you, whether you are an Employer or an aspiring Employee. Your skills and needs meet here. We make it easy to find trusted freelancers or flexible work that fits your lifestyle. No complicated steps. Just local opportunities, real people, and a platform built to help Angelenos.</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <form id="searchForm" class="d-flex" role="search">
                        <input id="searchInput" class="form-control me-2 border-1 border-dark" type="search" placeholder="Discover popular jobs..." aria-label="Search">
                        <button class="btn btn-danger" type="submit">Search</button>
                    </form>

                    <div id="searchResults" class="mt-3"></div>
                </div>
            </div>
        </div>
    </header>
    <div id="carouselcontent" class="container carousel slide py-3 mb-3" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-7">
                            <h1 class="text-dark display-6 fw-bold">Looking for Workers?</h1>
                            <p class="text-dark">Need a reliable helping hand? Whether it's a one-time task or an ongoing project, find skilled freelancers in Angeles City ready to work, online or in person. <br><br>Post a job for free and connect with trusted locals who are passionate, punctual, and ready to get things done. From creative work to daily errands, we’ve got the right talents for you.</p>
                            <button onclick="window.location.href='login.php';" class="btn btn-danger w-50" onclick="login.php">Employ now!</button>
                        </div>
                        <div class="col-md-5 d-none d-md-block" style="background-image: url('../images/427871806_948960113530614_2763071840680278191_n.jpg');
                        background-size: cover; 
                        background-position: center;
                        background-repeat: no-repeat;
                        border-radius: 20px;"></div>
                    </div>
                </div>
                
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-7">
                            <h1 class="text-dark display-6 fw-bold">Looking for Jobs?</h1>
                            <p class="text-dark">Find flexible jobs that match your skills, lifestyle, and goals, whether you're working from home or helping neighbors right here in Angeles City. From quick jobs to long-term projects, we make it easy to connect with employers who value your time and talent. <br><br>Join for free, build a profile that showcases your strengths, and start earning on your own terms. No office. No long commutes. Just opportunities, where and when you want them.</p>
                            <button onclick="window.location.href='signup.php';" class="btn btn-danger w-50" onclick="login.php">Apply now!</button>
                        </div>
                        <div class="col-md-5 d-none d-md-block" style="background-image: url('../images/490790031_1610160639644167_3002709841025943956_n.jpg'); 
                        background-size: cover; 
                        background-position: center;
                        background-repeat: no-repeat;
                        border-radius: 20px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselcontent" data-bs-slide="prev">
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselcontent" data-bs-slide="next">
            <span class="visually-hidden">Next</span>
          </button>
    </div>

    <footer class="py-3 mb-3 text-center">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="display-6 text-dark fw-bold text-center d-none d-md-block">Join the&nbsp;<span class="text-dark">Community</span></h1>
                    <p class="text-center d-none d-md-block text-dark">Become part of skilled freelancers and professionals right here in Angeles City. Whether you're just getting started or already freelancing, our community is here to support you every step of the way.</p>
                    <h1 class="display-6 text-dark fw-bold text-start d-block d-md-none">Join the&nbsp;<span class="text-dark">Community</span></h1>
                    <p class="text-start d-block d-md-none text-dark">Become part of skilled freelancers and professionals right here in Angeles City. Whether you're just getting started or already freelancing, our community is here to support you every step of the way.</p>
                    <button class="btn btn-danger w-50" onclick="window.location.href='signup.php'">Join now!</button>
                </div>
            </div>
        </div>
        
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>