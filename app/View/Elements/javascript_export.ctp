<?php if (Configure::read('Export')): ?>
<script>
	var _Server = <?=json_encode(Configure::read('Export'))?>;
	console.log(_Server);
</script>
<?php endif; ?>
<script>
	var Core={};Core.persistUrl=function(e){if(typeof e==="undefined"){return""}var t={};if(typeof arguments[1]==="object"){t=arguments[1]}var n=[];var r,i;if(_Server.Persists){for(r in _Server.Persists){i=_Server.Persists[r];if(typeof _Server[i]!=="undefined"&&typeof t[r]==="undefined"){n.push(r+"="+encodeURIComponent(_Server[i]))}}}var s;for(r in t){s="";if(t[r]!==null&&typeof t[r]!=="undefined"){s=t[r]}n.push(r+"="+encodeURIComponent(s))}if(n.length>0){e+=(e.indexOf("?")>=0?"&":"?")+n.join("&")}return e};
</script>