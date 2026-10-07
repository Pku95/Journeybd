document.getElementById('yr').textContent=new Date().getFullYear();
var q=new URLSearchParams(location.search);
document.querySelectorAll('[data-filter]').forEach(function(box){var a=box.dataset.filter,cards=document.querySelectorAll('[data-'+a+']:not(button)'),btns=box.querySelectorAll('button');
function set(v){btns.forEach(function(b){var on=b.dataset.v===v;b.classList.toggle('!bg-teal-700',on);b.classList.toggle('!text-white',on)});cards.forEach(function(c){c.style.display=!v||c.dataset[a]===v?'':'none'})}
btns.forEach(function(b){b.onclick=function(){set(b.dataset.v)}});set(q.get(a==='cat'?'category':a)||'')});
['from','to'].forEach(function(k){var i=document.querySelector('[name=x_'+k+']');if(i&&q.get(k))i.value=q.get(k)});
document.querySelectorAll('form[data-booking]').forEach(function(f){f.onsubmit=function(e){e.preventDefault();var m=f.querySelector('[data-msg]'),b=f.querySelector('button');b.disabled=true;m.className='mt-3 text-sm text-slate-600';m.textContent='Sending…';
fetch(f.action,{method:'POST',body:new FormData(f)}).then(function(r){return r.json()}).then(function(j){b.disabled=false;if(j.ok){f.reset();m.className='mt-3 rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800';m.innerHTML='✓ Request received. Reference: <b>'+j.reference+'</b>. We will contact you within 24 hours.'}else{m.className='mt-3 text-sm text-rose-600';m.textContent=j.error||'Something went wrong.'}}).catch(function(){b.disabled=false;m.className='mt-3 text-sm text-rose-600';m.textContent='Network error. Please try again or call us.'})}});
