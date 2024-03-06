<?php
// Include the Joomla configuration.php file
require_once '/home/path/to/configuration.php';

// Create a new JConfig object
$config = new JConfig();

// Extract the database connection details from the JConfig object
$host = $config->host;
$dbname = $config->db;
$user = $config->user;
$password = $config->password;
$prefix = $config->dbprefix;

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
    //Delete Submission Values where Payment Status is pending//
  $sql = "DELETE FROM `nipd_rsform_submission_values` 
WHERE `SubmissionId` IN (SELECT * FROM (SELECT `SubmissionId` FROM `nipd_rsform_submission_values` WHERE `FieldName` = '_STATUS' AND `FieldValue` = 0
    ) AS subquery
)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Optionally, you can output the number of affected rows
    $affectedRows = $stmt->rowCount();
    echo "Deleted $affectedRows records.";

    
    // Next Delete Submissions
    $sql2 = "DELETE FROM `nipd_rsform_submissions` 
             WHERE NOT EXISTS (
                SELECT 1 FROM `nipd_rsform_submission_values` 
                 WHERE `nipd_rsform_submissions`.`SubmissionId` = `nipd_rsform_submission_values`.`SubmissionId`
            );";

    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute();

    // Optionally, output the number of affected rows
    $affectedRows2 = $stmt2->rowCount();
    echo "Deleted $affectedRows2 submissions.";
    
    
} catch (PDOException $e) {
    // If there is an error, it will be caught here
    echo "Error: " . $e->getMessage();

    
}
