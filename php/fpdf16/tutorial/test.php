<?php
  echo '<meta http-equiv="Content-Type" content="text/html; charset=big5">';
  //create file
  $fp = fopen('hello.pdf', 'w');
  if(!$fp)
  {
    echo "Error: could not create the PDF file";
    exit;
  }

  // start the pdf document
  $pdf = pdf_open($fp);
  pdf_set_info($pdf, "Creator", "pdftest.php");
  pdf_set_info($pdf, "Author", "Shen Chun-Hsing");
  pdf_set_info($pdf, "Title", "Hello World (PHP)");

  // US letter is 11" x 8.5" and there are approximately 72 points per inch
  pdf_begin_page($pdf, 8.5*72, 11*72/2);
  pdf_add_outline($pdf, 'Page 1');

  // 取得字型
  $Efont = pdf_findfont($pdf, 'Times-Roman', 'host', 0);
  $Cfont = pdf_findfont($pdf, 'MSung-Light', 'ETen-B5-H', 0);

  // write text
  pdf_setfont($pdf, $Efont, 24);
  pdf_set_text_pos($pdf, 50, 700/2);
  pdf_show($pdf,'Hello world!');
  pdf_continue_text($pdf,'(says PHP)');
  pdf_setfont($pdf, $Cfont, 24);
  pdf_continue_text($pdf,'細明體中文字測試');

  // end the document
  pdf_end_page($pdf);
  pdf_close($pdf);
  fclose($fp);

  // display a link to download
  echo "<a href = 'hello.pdf' target='_blank'>測試檔PDF</a>";
  echo "<hr>";
  show_source( basename( getenv("SCRIPT_FILENAME") ) );
?>

