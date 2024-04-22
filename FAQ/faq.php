<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Management FAQ</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 20px;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
    }
    .faq-container {
      max-width: 600px;
      margin: 0 auto;
    }
    .faq-item {
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 15px;
      background-color: #fff;
    }
    .question {
      font-size: 18px;
      margin-bottom: 10px;
    }
    .answer {
      font-size: 16px;
      line-height: 1.5;
    }
  </style>
</head>
<body>

<!-- Heading for the FAQ page -->
<h1>Library Management FAQ</h1>

<!-- Container for displaying FAQ items -->
<div class="faq-container">
  <?php
  // Define an array of frequently asked questions and their answers
  $faq = array(
    "How do I borrow a book?" => "To borrow a book, visit the library's circulation desk and present your library card to the librarian. You can then choose the books you wish to borrow.",
    "Can I renew my borrowed books?" => "Yes, you can renew your borrowed books if they are not reserved by other users. You can do this by visiting the library's website or by contacting the circulation desk.",
    "What should I do if I lose a library book?" => "If you lose a library book, you are responsible for replacing it or paying for its replacement cost. Please inform the library staff immediately to discuss the necessary steps.",
    // Add more questions and answers as needed
  );

  // Display the FAQ items
  foreach ($faq as $question => $answer) {
    echo "<div class='faq-item'>";
    echo "<h3 class='question'>$question</h3>"; // Display the question
    echo "<p class='answer'>$answer</p>"; // Display the answer
    echo "</div>";
  }
  ?>
</div>

</body>
</html>
