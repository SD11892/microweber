<?php
/*
	Build html tables by Ezyweb.uk v1.0 14/09/2018

	Future enharnsements:
	1) add context sensitive menu to insert, mover, or delete cells, rows, and columns
	2) add cell styling using cell classes
	3) add template using jexcel.js https://bossanova.uk/jexcel
	4) add template using bootgrid
	5) add import csv option
	6) add template to re-orientate on smaller screens https://codepen.io/AllThingsSmitty/pen/MyqmdM
*/
?>
<?php

only_admin_access();

$settings = get_option('settings', $params['id']);

$json = ($settings? $settings : '');
?>

<style>
#modtable {
  float:left;
  width:100%;
  overflow-y: auto;
  overflow-x: auto;
  height: 300px;
  border:1px solid #cacaca;
  margin-bottom:10px;
  margin-top:10px;
}

table {
  border-collapse:collapse;
}

th{
	height=10;
	border:1px solid #cacaca;
	background: rgb(231, 235, 245);
	padding:4px;
}

td {
	border:1px solid #cacaca;
	text-align:left;
	height=10px;
	padding:4px;
}

tr:nth-child(even) {
  background-color: #fafafa;
  height=10;
}

.tab{
  display: none;
}
</style>

<script>
function appendRow() {
    var tbl = document.getElementById('htmltable');
    var nrows = tbl.rows.length,
    row = tbl.insertRow(nrows),
    i;
    for (i = 0; i < tbl.rows[0].cells.length; i++) {
        var cellRef = 'r'+(nrows)+'c'+(i+1);
        createCell(row.insertCell(i), '', 'row '+cellRef);
    }
}

function createHeaderCell(row, text, style) {
    var headerCell = document.createElement("th");
    headerCell.innerHTML = text;
    headerCell.setAttribute('class', style);
    headerCell.setAttribute('className', style);
    headerCell.setAttribute('contenteditable', true);
    row.appendChild(headerCell);
}

function createCell(cell, text, style) {
    var txt = document.createTextNode(text);
    cell.setAttribute('class', style);
    cell.setAttribute('className', style);    // set className attribute for IE (?!)
    cell.setAttribute('contenteditable', true);
    cell.appendChild(txt);
}

// append column to the HTML table
function appendColumn() {
    var tbl = document.getElementById('htmltable'),
        i;
    for (i = 0; i < tbl.rows.length; i++) {
        var ncols = tbl.rows[i].cells.length;
        if(i==0){
           var cellRef = 'h'+(ncols+1);
           var text = 'Header '+(ncols+1);
		   createHeaderCell(tbl.rows[i], text, 'th '+cellRef);
        } else {
          var cellRef = 'r'+(i)+'c'+(ncols+1);
          createCell(tbl.rows[i].insertCell(ncols), '', 'col '+cellRef);
        }
    }
}

function deleteRow(row='') {
    var tbl = document.getElementById('htmltable');

    if(row == 'last') {
        lastRow = tbl.rows.length - 1;
        tbl.deleteRow(lastRow);
    } else if(row == 'all') {
    	lastRow = tbl.rows.length - 1;
		// delete rows including header with index greater then 0
		for (i = lastRow; i >= 0; i--) {
			tbl.deleteRow(i);
		}
    } else if(row == 'allbutone') {
    	lastRow = tbl.rows.length - 1;
		// delete rows except header
		for (i = lastRow; i > 0; i--) {
			tbl.deleteRow(i);
		}
    } else if(row != ''){
        tbl.deleteRow(row);
	} else {
		alert('row not set in function deleteRow');
	}
}

function deleteColumn(col='') {
    var tbl = document.getElementById('htmltable');

    if(col == 'last') {
    	lastCol = tbl.rows[0].cells.length - 1;
		for (i = 0; i < tbl.rows.length; i++) {
		   tbl.rows[i].deleteCell(lastCol);
		}
    } else if(col == 'all') {
		lastCol = tbl.rows[0].cells.length - 1;
		// delete cells with index greater than or equal to 0 (for each row)
		for (i = 0; i < tbl.rows.length; i++) {
			for (j = lastCol; j >= 0; j--) {
				tbl.rows[i].deleteCell(j);
			}
		}
    } else if(col == 'allbutone') {
		lastCol = tbl.rows[0].cells.length - 1;
		// delete cells with index greater then 0 (for each row)
		for (i = 0; i < tbl.rows.length; i++) {
			for (j = lastCol; j > 0; j--) {
				tbl.rows[i].deleteCell(j);
			}
		}
    } else if(col != ''){
		for (i = 0; i < tbl.rows.length; i++) {
		   tbl.rows[i].deleteCell(col);
		}
	} else {
		alert('col not set in function deleteColumn');
	}
}

// ---- build table functions ----

// Build HTML Table from json data
function buildTable(tableId,jdata) {
	var elTable = jQuery("#"+tableId);
	var columns = addColumnHeaders(elTable,jdata);
	var tbody = elTable.find("tbody");
	if (tbody.length===0){  //if there is no tbody element, add one.
		tbody = jQuery("<tbody></tbody>").appendTo(elTable);
	}
    for (var i = 0; i < jdata.length; i++) {
        var row$ = $('<tr/>');
        for (var colIndex = 0; colIndex < columns.length; colIndex++) {
            var cellValue = jdata[i][columns[colIndex]];
            var tdClass = "col r"+i+"c"+colIndex;
            if (cellValue == null) {
                cellValue = "";
            }
            row$.append($('<td/>').html(cellValue).addClass(tdClass).attr('contenteditable','true'));
        }
        tbody.append(row$);
    }
}

// Adds a header row to the table and returns the set of columns.
// Need to do union of keys from all records as some records may not contain
// all records
function addColumnHeaders(elTable,jdata) {
    var columnSet = [];
    var headerTr$ = $('<tr/>');
    var thead = elTable.find("thead");
	if (thead.length===0){  //if there is no thead element, add one.
		thead = jQuery("<thead></thead>").appendTo(elTable);
	}
    for (var i = 0; i < jdata.length; i++) {
        var rowHash = jdata[i];
        var c = 0;
        for (var key in rowHash) {
            c++;
            var thClass = "th h"+c;
            if ($.inArray(key, columnSet) == -1) {
                columnSet.push(key);
                headerTr$.append($('<th/>').html(key).addClass(thClass).attr('contenteditable','true'));
            }
        }
    }
    thead.append(headerTr$);
    return columnSet;
}

// ---- button click functions -----

$(document).ready(function () {

	<?php if(!empty($json)) { ?>
	try {
	  var json = <?php print $json;?>;
	  var jdata = json.tabledata;
	  var tableId = "htmltable";
	  deleteRow('all');
	  buildTable(tableId,jdata)
	} catch (e) {
	  // No data found so default table will load
	}
	<?php } ?>

	$(document).on('click','#saveData',function(event){
		var myRows = [];
		var headersText = [];
		//TODO: save data to settings and add general and styles keys
		var tableCss = []; // place holder
		var $headers = $("th");
		var $rows = $("tbody tr").each(function(index) {
		  $cells = $(this).find("td");
		  myRows[index] = {};
		  $cells.each(function(cellIndex) {
			if(headersText[cellIndex] === undefined) {
			  headersText[cellIndex] = $($headers[cellIndex]).html();
			}
			myRows[index][headersText[cellIndex]] = $(this).html();
		  });
		});
		var myObj = {
			"tabledata": myRows,
			"tablecss": tableCss
		};
		var json = JSON.stringify(myObj);
		mw.$('#settingsfield').val(json).trigger('change');
	});
});
</script>

<div class="module-live-edit-settings">

	<script>
	$(mwd).ready(function(){
	  mw.tabs({
		nav:'.mw-ui-btn-nav-tabs a',
		tabs:'.tab'
	  })
	})
	</script>

	<input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>

	<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
		<a class="mw-ui-btn active" href="javascript:;"><?php _e("Table Builder"); ?></a>
		<a class="mw-ui-btn" href="javascript:;"><?php _e("Settings"); ?></a>
	</div>


	<div class="mw-ui-box mw-ui-box-content">

		<div class="tab" style="display: block">
				<div class="mw-ui-field-holder">
					<div id="modtable">
						<table id="htmltable" align="left" cellspacing="0" celpadding="0">
							<thead>
								<tr>
									<th class="th h1" classname="th h1" contentEditable="true">Header 1</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="col r1c1" classname="col r1c1" contentEditable="true"></td>
								</tr>
							</tbody>
						</table>
					</div>

					<input class="mw-ui-btn" type="button" value="Add Row" onclick="appendRow()" />
					<input class="mw-ui-btn" type="button" value="Add Column" onclick="appendColumn()" />
					<input class="mw-ui-btn" type="button" value="Delete Last Row" onclick="deleteRow('last')" />
					<input class="mw-ui-btn" type="button" value="Delete Last Column" onclick="deleteColumn('last')" />
					<input class="mw-ui-btn" type="button" value="Delete All Rows" onclick="deleteRow('all')" />
					<input class="mw-ui-btn" type="button" value="Delete All Columns" onclick="deleteColumn('all')" />
					<input class="mw-ui-btn" type="button" value="Delete All" onclick="deleteRow('all'); deleteColumn('all')" />
					<br>
					<button class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert pull-right" id="saveData" >Save</button>
				</div>
		</div>

		<div class="tab">
			<div class="mw-ui-field-holder">
				<label class="mw-ui-label"><?php _e("Table Settings"); ?></label>
			</div>
<?php // MODULE NOT SHOWING ?>
            <module type="admin/modules/templates" simple="true"/>
		</div>

	</div>
</div>