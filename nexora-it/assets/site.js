(() => {
  const button = document.querySelector('.menu-toggle');
  const navigation = document.getElementById('primary-navigation');
  if (!button || !navigation) return;
  document.documentElement.classList.add('js');
  button.hidden = false;
  const close = () => { navigation.classList.remove('is-open'); button.setAttribute('aria-expanded', 'false'); };
  button.addEventListener('click', () => {
    const opened = navigation.classList.toggle('is-open');
    button.setAttribute('aria-expanded', String(opened));
  });
  navigation.addEventListener('click', event => { if (event.target.closest('a')) close(); });
  document.addEventListener('keydown', event => { if (event.key === 'Escape' && navigation.classList.contains('is-open')) { close(); button.focus(); } });
})();
