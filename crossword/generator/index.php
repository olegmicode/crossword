<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Crossword Generator</title>

    <link type="text/css" rel="stylesheet" href="css/styles.css"/>
    <script type="text/javascript" src="../crossword/js/jquery-2.1.4.min.js"></script>


</head>

<body>

<a href="#" id="regenerate">Regenerate</a>

<div id="generator">

    <ul>
        <li>
            <div class="line">
                <span class="label">Number of rows/cols:</span>
                <input id="size" type="text" value="15" class="cw_size"/>
            </div>
        </li>




    </ul>


    <hr/>


    <div class="half_width">
        <div id="crossword"></div>
    </div>


    <div class="half_width">
        <table id="clues">
            <thead>
            <tr>
                <th>Across</th>
                <th>Down</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <ul id="across"></ul>
                </td>
                <td>
                    <ul id="down"></ul>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</div>

<a href="#" id="copy" data-clipboard-target="#output">Copy JSON</a>

<div id="output">Output</div>
<form action="../crossword/index.php" method="post" id="form">
  <textarea name="json" id="json"></textarea>
  <button type="submit" id="submit">Submit</button>
</form>
<script>
$( document ).ready(function() {
  $('#size').val(20)
  console.log($('#output pre').text())
  //sendData()

  function sendData(){
    q = <?php echo $_GET['q']?>;
    //randomvalue = Math.floor(Math.random() * 2) + 1;
    count_q = typeof q=='undefined'?10:q;
    $('#size').val(count_q);
    setTimeout(function(){
      //console.log('output:'+$('#output pre').text());
      $('#json').html($('#output pre').text());
      // submit to crossword
      if ($('#json').html()!=''){
        $('#form').attr('action','../crossword/index.php?level=<?php echo $_GET['level']?>&generator=true&no=<?php echo $_GET['no']?>')
        $('#submit').click();
      }else{
        alert('Data empty')
      }

    }, 1000);

  }
});
</script>
<script type="text/javascript" src="data/words-clues-middle.js"></script>
<script type="text/javascript" src="data/settings.js"></script>
<script type="text/javascript" src="data/labels.js"></script>


<script type="text/javascript" src="js/crossword.js"></script>
<script type="text/javascript" src="js/clipboard.min.js"></script>
<script type="text/javascript" src="js/generator.js"></script>

</body>
</html>
