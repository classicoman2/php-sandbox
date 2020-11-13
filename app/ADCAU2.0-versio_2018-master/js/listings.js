/** 
 * Funcions JAVASCRIPT i JAVAQUERY utilitzades pels llistats.
 */

$( function() {
    // ON Click checkbox to Select ALL
    $('#selectAll_btn').click( function (e) {
        //Get the number of Rows 
        var numRows = document.getElementById('max').value
        var box = document.getElementById('selectAll_btn');
        var value;
        if (box.checked)
            value = true;
        else
            value = false;
        //Tick/untick the Checkboxes
        for (i=0;i<numRows;i++) {
            document.getElementById('check_list'+i).checked = value;
        }
    });
});



/**
 * Mostra la finestra amb fondo negre, on l'usuari pot seleccionar quines columnes
 * de la taula vol mostrar.
 * 
 * @param {type} tb
 * @param {type} tableCols
 * @returns {undefined}
 */
function obreFinestraOptions(tb) {
    try {
        //Mostra la Finestra
        $('#parameters').css( { opacity: 1 } );
        $('#parameters').css('zIndex',1);
        $('#sbox-curtina').show();
        $('#sbox-window').css( { opacity: 1 } );
        $('#sbox-window').show();

        //Carrega els noms dels camps amb un checkbox a la dreta, per a que 
        //poguem seleccionar quins volem mostrar o amagar.
        $.ajax({
            type: "GET",
            url: 'main.php?pg=ajax&file=optionsWindowX&tb='+tb,
            async: /*true*/false,
            success : function(resultat) {
                $("#"+'optionsDiv').html(resultat);
            }
        });
    } catch(e) { alert(e); }
}  
  
  
/**
 * Tanca la finestra de Opcions i crida el PHP mitjançant ajax per a recarregar
 * les files.
 * 
 * @param {type} f
 * @returns {undefined}
 */
function tancaFinestraOptions(f) {
    //amaga div parameters
    $('#parameters').css( { opacity: 1 } );
    $('#parameters').css('zIndex',-1);
    //amaga la curtina
    $('#sbox-curtina').hide();
    //amaga la finestra 
    $('#sbox-window').css( { opacity: 1 } );
    $('#sbox-window').hide();
    
    //Ajax
    $.ajax({
        type: "GET",  url: f,  async: true,
        success : function(resultat) {
            $("#"+'div_rows').html(resultat);
        }
    });
}



/**
 * 
 * @param {type} tb
 * @param {type} tableCols
 * @param {type} div
 * @returns {undefined}
 */
function saveOptions(tb, tableCols, div) {
    /**
     * Detect click upon the Checkboxes of Filter Columns
     * 
     * @param {type} fields
     * @returns {String}
     */
    function fieldsFilteringGetParams(fields) 
    {
        var arr, i, value, checkedFields="";
        //THIS IS A HIDDEN FIELD IN THE FORM THAT WE'RE USING AS A VARIABLE!
        arr = fields.split('-');
        for(i in arr) {
            id = "chb_"+arr[i];
            value = (document.getElementById(id).checked)  ?  "1" : "0";
            checkedFields += value;
        }    
        return checkedFields;
    }

    //Get the input field hidden variables inside table_fields.php
    try {
        var chbvalues = fieldsFilteringGetParams(tableCols);
    } 
    catch(e) {
        alert('Hi ha un error de JS: '+e);
    }
        
    var f = "main.php?pg=ajax&file=optionsWindowSaveX&tb="+tb+"&chbvalues="+chbvalues;

    //Ajax mitjançant JQUERY
    $.ajax({
        type: "GET",  url: f,  async: true,
        success : function(resultat) {
            $("#"+div).html(resultat);
        }
    });
}



/*
 * Botons comuns a tots els llistats
 */
$( document ).ready(function() {   
        //En espitjar botó amb ròtul: 'Esborrar'
        $('#btnDelete').click( function() {
            if (confirm('Voleu esborrar tots aquests registres?')) 
                document.getElementById('listForm').submit();
        });
});
