<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GradBridge - Where Alumni Meet Ambition</title>
    <meta name="description" content="A trusted space where students connect with alumni for mentorship, guidance, and real opportunities to grow and thrive." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css?v=<?=time()?>">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css?v=<?=time()?>">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/header.css?v=<?=time()?>">

    <!-- <link rel="stylesheet" href="http://localhost/gradb2/public/assets/css/Main.css">
    <link rel="stylesheet" href="http://localhost/gradb2/public/assets/css/other.css">
    <link rel="stylesheet" href="http://localhost/gradb2/public/assets/css/header.css"> -->
    <!-- http://localhost/gradb2/public -->
  </head>

  <body>
    <!-- Header -->
    <header class="header">
      <div class="container">
        <div class="header-content">
          <div class="logo">
            GradBridge
          </div>
          
          <button class="menu-toggle" aria-label="Open menu">≡</button>
          <nav class="nav">
            <a href="<?=ROOT?>/home" class="nav-link">Home</a>
            <a href="<?=ROOT?>/home#about" class="nav-link">About</a>
            <a href="<?=ROOT?>/home#features" class="nav-link">Features</a>
            <a href="<?=ROOT?>/login" class="nav-link">Login</a>
          </nav>

          <a class="btn btn-primary" href="<?=ROOT?>/signup">
            Register
          </a>
        </div>
      </div>
    </header>