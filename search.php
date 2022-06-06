<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="search.css">
<script type="text/javascript" src="search.js"></script>
<title>Vyhledávání</title>
</head>
<body>
    <?php require_once('common.php')?>
    
    <form id="form" autocomplete="off" action="javascript:void(0);" style="position:fixed;top:50%;left:50%;transform: translate(-50%, -50%)">
        <div class="verLayout">
            <div class="horLayout">
                <div class="cell" style="padding-right:10px;text-align:right">
                    Odkud
                </div>
                <div class="cell">
                    <div class="autocomplete">
                        <input id="from" type="text" name='from' >
                    </div>
                </div>
            </div>
        
            <div class="horLayout">
                <div class="cell" style="padding-right:10px;text-align:right">
                    Kam
                </div>
                <div class="cell">
                    <div class="autocomplete">
                        <input id="whereto" type="text" name='whereto' >
                    </div>
                </div>
            </div>

            <div class="horLayout">
                <div class="cell" style="padding-right:10px;text-align:right">
                    Čas odjezdu
                </div>
                <div class="cell" >
                    <input id="time" type="time" name="time" placeholder="Čas" style="font-family:Arial;" required>
                    <input type="submit" value="Hledat" style="width:100%;">
                </div>
            </div>
            
            
            
        </div>
    </form>

<script>
    <?php require_once('parse_table.php');?>
    var array = <?php echo parse_column('stop', 'position');?>;
    autocomplete(document.getElementById("from"), array);
    autocomplete(document.getElementById("whereto"), array);
    var d = new Date();
    // Need to create UTC time of which fields are same as local time.
    d.setUTCHours(d.getHours(), d.getMinutes(), 0, 0);
    document.getElementById("time").valueAsDate = d;

    attachFormSubmitEvent("form");
    
    function attachFormSubmitEvent(formId){
        document.getElementById(formId).addEventListener("submit", formSubmit);
    }

    function formSubmit(){
        var from = document.getElementById("from");
        var whereto = document.getElementById("whereto");
        var correct = true;
        
        if(from.value == "" || array[from.value] == null){
            correct = false;
            from.style.border="2px solid red";
        } else {
            from.style.border="none";
        }

        if(whereto.value == "" || array[whereto.value] == null){
            correct = false;
            whereto.style.border="2px solid red";
        } else {
            whereto.style.border="none";
        }

        if (correct){
            if(array[from.value] == array[whereto.value]){
                from.style.border="2px solid red";
                whereto.style.border="2px solid red";
                correct = false;
            } else {
                whereto.style.border="none";
                from.style.border="none";
            }
        }

        if (document.getElementById("time").value === ""){
            correct = false;
            document.getElementById("time").style.border="2px solid red";
        } else {
            document.getElementById("time").style.border="none";
        }

        

        if(correct){
            var redirect = "searchResults.php?"
            location.href = redirect.concat("from=", array[from.value], "&whereto=", array[whereto.value], "&time=", document.getElementById("time").value);
        } else {

        }
        //console.log(document.getElementById("from").value);
    }
</script>
</body>
</html>
