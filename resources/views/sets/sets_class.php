	<style type="text/css">
    	#all {
    		margin: 20px auto;
    		min-width: 850px;
            width: 90%;
    	}
    	.cen {
    		margin: 0 auto;
    		padding: 20px 0px 10px 0px;
    		margin: 0px 20px 0px 20px;
    	}
    	#sets_title {
    		height: 30px;
    		line-height: 30px;
    		margin-bottom: 5px;
    		background-color: white;
    		border-bottom: 1px #B4B5B5 solid;
    		border-right: 1px #B4B5B5 solid;
    		border-left: 1px #B4B5B5 solid;
    	}
    	.title {
    		height: 30px;
    		line-height: 30px;
    		margin-bottom: 5px;
    		background-color: #F2D9B6;
    		border-bottom: 1px #B4B5B5 solid;
    		border-right: 1px #B4B5B5 solid;
    		border-left: 1px #B4B5B5 solid;
    		float: left;
    		width: 100%;
    	}
    	#sets_title label{
    		margin-left: 20px;
    	}
    	.title label {
    		margin-left: 20px;
    	}
    	.input_field {
    		margin:0px;
    	}
    	.btn {
    		font-size: 14px;
    	}
    	.f14{
    		margin-left: 5px;
    		margin-right: 5px;
    	}
    	.deep {
    		background-color: #F5F5F4;
    	}
    	.shallow {
    		background-color: #FCFCFC;
    	}
    	.shallow td{
    		padding: 10px 0px 10px 0px;
    	}
    	.list tr td{
    		margin-bottom: 10px;
    		height: 25px;
    		line-height: 25px;
    		padding-left: 10px;
    		vertical-align: top;
    	}
    	.list label {
    		margin-right: 5px;
    	}
    	.list input {
    		margin-right: 5px;
    	}
    	.list {
    		margin-bottom: 20px;
    	}
    	#oans {
    		display: none;
    	}
    	select {
    		margin-right: 5px;
    	}
	</style>
<body>
<div id="all">
	<div class="title"><label class="f17"><?=$title?></label></div>
	<FORM name="form1" method="POST" action="<?=site_url('/sets/uclass/').'/'.$Setid?>" onsubmit="return check();">
    <div class="content">
		<div class="cen">
            <font color="#AE0000" id="class_msg"><?=$Msg?></font>
			<table class="list" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr class="deep">
                    <td width="100" align="right">試卷名稱</td>
                    <td><?=$Setsname?></td>
                </tr>
				<tr class="shallow">
                    <td align="right">分校</td>
                    <td>
                        <select name="fg" id="fg" onchange="change_c(this.value)">
                            <?=$Web->g?>
                        </select>
                    </td>
                </tr>
                <tr class="deep">
                    <td align="right">班級</td>
                    <td>
                        <div style="max-height:200px; width:300px; overflow:auto;">
                        <select name="fc" id="fc" onchange="change_ca(this.value)">
                            <?=$Web->c?>
                        </select>
                        </div>
                    </td>
                </tr>
                <tr class="shallow">
                    <td width="40" align="right">班別</td>
                    <td><label><input type="checkbox" name="all" onclick="check_all(this,'caid[]')">全部</label>
                        <div style="max-height:200px; width:300px; overflow:auto;" id="fca"><?=$Web->ca?></div>
                    </td>
                </tr>
            </table>
		</div>
	</div>
    <div class="content" style="margin-bottom:50px;">
        <div class="cen" style="padding-bottom:50px;">
            <div style="text-align:left;">
                <input type="submit" class="btn w150 h30" value="完成" name="save" id="save">
            </div>
        </div>
    </div>
    </form>
</div>
</body>
</html>
<script type="text/javascript">
window.moveTo(100,100);
//window.moveTo(100,100);window.resizeTo(900,450);window.focus();
function trim(value){
    return value.replace(/^\s+|\s+$/g, '');
}
function check(){
    var ca = $('#fca input:checkbox[name="caid[]"]:checked').val();
    if (isNaN(ca)){
        document.getElementById('class_msg').innerHTML = '至少選一個班別<br><br>';
        return false;
    }
}
function change_c(g){//取得班級
    $('#fc').html('<option value="0">讀取中</option>');
    $.ajax({
        type:"GET",
        url:"<?=site_url('/Apig/ajgca')?>",
        data:{type:"c", g:g},
        dataType: "JSON",
        success:function(data){
            $('#fc option').remove();
            var html = '';
            for(var i in data){
                html+= '<option value="'+data[i].id+'">'+data[i].name+'</option>';
            }
            $('#fc').html(html);
            change_ca(data[0].id);
        }
    });
}
function change_ca(c){//切換班級取得班別，並動態增加
    $('#fca').html('讀取中');
    $.ajax({
        type:"GET",
        url:"<?=site_url('/Apig/ajgca')?>",
        data:{type:"ca", c:c},
        dataType: "JSON",
        success: function(data){
            var html = '';
            for (var i in data){
                html+= '<label><input type="checkbox" name="caid[]" value="'+data[i].id+'">'+data[i].name+'</label><br>';
            }
            $('#fca').html(html);
        }
    });
}
function check_all(obj,cName){
    var checkboxs = document.getElementsByName(cName);
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;}
}
</SCRIPT>