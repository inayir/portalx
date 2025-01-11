/*
	ReadURL: bir resim dosyasını alıp bir img içinde gösterir.
*/
function readURL(input, res) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#'+res).attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}
function startTime() {
  const today = new Date();
  let y = today.getFullYear();
  let a = today.getMonth();
  let g = today.getDay();
  let h = today.getHours();
  let m = today.getMinutes();
  let s = today.getSeconds();
  g = checkTime(g);
  a = checkTime(a);
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('tarihsaatsec').innerHTML =  g+'.'+a+'.'+y+' '+h + ":" + m + ":" + s;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}

/*bir json array içinde obje aramak*/
function getObjects(obj1, key, val) {
    var objects = [];
    for (var i in obj1) {
        if (!obj1.hasOwnProperty(i)) continue;
        if (typeof obj1[i] == 'object') {
            objects = objects.concat(getObjects(obj1[i], key, val));
        } else if (i == key && obj1[key] == val) {
            objects.push(obj1);
        }
    }
    return objects;
}