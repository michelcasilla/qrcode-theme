<?php

downloadCsv();


function downloadCsv(){

    global $wpdb;

    $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}event_payments");
    // if(is_admin()){
        //   $data = array(
        //       array('name' => 'A', 'mail' => 'a@gmail.com', 'age' => 43),
        //       array('name' => 'C', 'mail' => 'c@gmail.com', 'age' => 24),
        //       array('name' => 'B', 'mail' => 'b@gmail.com', 'age' => 35),
        //       array('name' => 'G', 'mail' => 'f@gmail.com', 'age' => 22),
        //       array('name' => 'F', 'mail' => 'd@gmail.com', 'age' => 52),
        //       array('name' => 'D', 'mail' => 'g@gmail.com', 'age' => 32),
        //       array('name' => 'E', 'mail' => 'e@gmail.com', 'age' => 34),
        //       array('name' => 'K', 'mail' => 'j@gmail.com', 'age' => 18),
        //       array('name' => 'L', 'mail' => 'h@gmail.com', 'age' => 25),
        //       array('name' => 'H', 'mail' => 'i@gmail.com', 'age' => 28),
        //       array('name' => 'J', 'mail' => 'j@gmail.com', 'age' => 53),
        //       array('name' => 'I', 'mail' => 'l@gmail.com', 'age' => 26),
        //   );
      
      $fileName_1 = 'Manifest.csv';
              header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
              header('Content-Description: File Transfer');
              header("Content-type: text/csv");
              header("Content-Disposition: attachment; filename={$fileName_1}");
              header("Expires: 0");
              header("Pragma: public");
              $fh1 = @fopen( 'php://output', 'w' );
              $headerDisplayed1 = false;
      
              foreach ( $data as $data1 ) {
                  // Add a header row if it hasn't been added yet
                  if ( !$headerDisplayed1 ) {
                      // Use the keys from $data as the titles
                      fputcsv($fh1, array_keys($data1));
                      $headerDisplayed1 = true;
                  }
      
                  // Put the data into the stream
                  fputcsv($fh1, $data1);
              }
          // Close the file
              fclose($fh1);
          // Make sure nothing else is sent, our file is done
              exit;
        //   }
}