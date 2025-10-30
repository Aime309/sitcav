function testAnim(x) {
  document.getElementById('animationSandbox').classList.remove();
  document.getElementById('animationSandbox').classList.add('animate__animated', 'animate__' + x);
  document.getElementById('animationSandbox').addEventListener('webkitAnimationEnd', removeClasses);
  document.getElementById('animationSandbox').addEventListener('mozAnimationEnd', removeClasses);
  document.getElementById('animationSandbox').addEventListener('MSAnimationEnd', removeClasses);
  document.getElementById('animationSandbox').addEventListener('oanimationend', removeClasses);
  document.getElementById('animationSandbox').addEventListener('animationend', removeClasses);
}

function removeClasses() {
  document.getElementById('animationSandbox').classList.remove();
}

document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('.js--triggerAnimation').addEventListener('click', function(e) {
    e.preventDefault();
    var anim = document.querySelector('.js--animations').value;
    testAnim(anim);
  });

  document.querySelector('.js--animations').addEventListener('change', function() {
    var anim = this.value;
    testAnim(anim);
  });
});