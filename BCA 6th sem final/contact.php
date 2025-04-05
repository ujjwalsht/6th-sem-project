<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COntact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 20px;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .details h2 {
            color: #333;
        }
        .details p {
            margin-bottom: 10px;
        }
        .photo {
            max-height: 250px;
            max-width: 300px;
            border-radius: 20%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact details</h1>
            <div class="details">
            <div>
                <h2>Owner Detail</h2>
                <p>Owner Name: Purushottam Shrestha</p>
                <p>Email: sangamauto@gmail.com</p>
                <p>Phone: +977 9823553889</p>
            </div>
            <img src="IMG/owner_photo.jpg" alt="Owner Photo" class="photo">

            <div>
                <h2>Worker Details</h2>
                <div>
                    <h3>Worker 1</h3>
                    <p>Worker Name: Amit Gurung</p>
                    <p></p>
                    <p>Phone: +977 9823565533</p>
                </div>
                <img src="IMG/worker_photo1.jpg" alt="Worker Photo 1" class="photo">
                
                <div>
                    <h3>Worker 2 </h3>
                    <p>Worker Name: Ganesh Tamang</p>
                    <p></p>
                    <p>Phone: +977 9813310956</p>
                </div>
                <img src="IMG/worker_photo2.jpg" alt="Worker Photo 2" class="photo">
                
                <div>
                    <h3>Worker 3 </h3>
                    <p>Worker Name: Badal Rai</p>
                    <p></p>
                    <p>Phone: +977 9841123485</p>
                </div>
                <img src="IMG/worker_photo3.jpg" alt="Worker Photo 3" class="photo">
                
                <div>
                    <h3>Worker 4</h3>
                    <p>Worker Name: Munna alim</p>
                    <p></p>
                    <p>Phone: +977 9886240786   </p>
                </div>
                <img src="IMG/worker_photo4.jpg" alt="Worker Photo 4" class="photo">
            </div>
        </div>
    </div>
</body>
</html>
