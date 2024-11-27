<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Body Styling */
body {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #e3f2fd;
    color: #333;
    margin: 0;
}

.navbar {
    width: 100%;
    background-color: #1565c0;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.navbar h2 {
    color: #ffffff;
    font-size: 24px;
    font-weight: bold;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.navbar .nav-links {
    display: flex;
    gap: 20px;
}

.navbar .nav-links a {
    color: #ffffff;
    text-decoration: none;
    padding: 8px 15px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.2s;
}

.navbar .nav-links a:hover {
    background-color: #00509e;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Form Container */
form {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding-top: 60px;
        }

        /* Form Heading */
        form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Floating Labels */
        .form-floating {
            position: relative;
            margin-bottom: 15px;
        }

        .form-floating input,
        .form-floating textarea {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .form-floating input:focus,
        .form-floating textarea:focus {
            border-color: #1565c0;
            outline: none;
        }

        .form-floating label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            font-size: 16px;
            color: #888;
            pointer-events: none;
            transition: top 0.3s, font-size 0.3s;
        }

        .form-floating input:focus ~ label,
        .form-floating textarea:focus ~ label,
        .form-floating input:not(:placeholder-shown) ~ label,
        .form-floating textarea:not(:placeholder-shown) ~ label {
            top: -8px;
            font-size: 12px;
            color: #1565c0;
        }

/* Textarea Styling */


/* Button Styling */
button[type="submit"] {
    background-color: #1565c0;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 12px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #0d47a1;
}

/* Responsive Styling */
@media (max-width: 768px) {
    form {
        padding: 20px;
    }

    .form-group input,
    .form-group textarea {
        padding: 10px 12px;
    }

    button[type="submit"] {
        padding: 10px;
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <div class="navbar">
        <h2>Smartcampus</h2>
        <div class="nav-links">
            <a href="./smartcampus.php">Home</a>
        </div>
    </div>

    <form action="./store_message.php" method="post">
        <h2>Contact Us</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name">
                   
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Your Email">
                   
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating">
                    <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject">
                    
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating">
                    <textarea class="form-control" id="message" name="messages" placeholder="Message" style="height: 150px"></textarea>
                   
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
            </div>
        </div>
    </form>
</body>
</html>
