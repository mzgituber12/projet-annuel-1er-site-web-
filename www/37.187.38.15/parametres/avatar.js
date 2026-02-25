const avatarPreview = document.getElementById("avatar-preview");
const savenewurl = document.getElementById("nouvurl");
const selectors = document.querySelectorAll("select");

function updateAvatar() {
  const avatarStyle = document.getElementById("avatarStyle").value;
  const topType = document.getElementById("topType").value;
  const eyeType = document.getElementById("eyeType").value;
  const skinColor = document.getElementById("skinColor").value;
  const accessoriesType = document.getElementById("accessoriesType").value;
  const hairColor = document.getElementById("hairColor").value;
  const facialHairType = document.getElementById("facialHairType").value;
  const clotheType = document.getElementById("clotheType").value;
  const eyebrowType = document.getElementById("eyebrowType").value;
  const mouthType = document.getElementById("mouthType").value;

  const url = `https://avataaars.io/?avatarStyle=${avatarStyle}&topType=${topType}&accessoriesType=${accessoriesType}&hairColor=${hairColor}&facialHairType=${facialHairType}&clotheType=${clotheType}&eyeType=${eyeType}&eyebrowType=${eyebrowType}&mouthType=${mouthType}&skinColor=${skinColor}`;

  if (avatarPreview) avatarPreview.src = url;
  if (savenewurl) savenewurl.value = url;
}
selectors.forEach((sel) => sel.addEventListener("change", updateAvatar));
