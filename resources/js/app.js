import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('contactPrefForm');
  const saveBtn = document.getElementById('saveContactPrefsBtn');
  if (!form || !saveBtn) return;
  const userId = form.getAttribute('data-user-id');
  const key = `contact_pref_${userId}`;

  const saved = localStorage.getItem(key);
  if (saved) {
    const input = form.querySelector(`input[name="contact_pref"][value="${saved}"]`);
    if (input) input.checked = true;
  }

  saveBtn.addEventListener('click', () => {
    const selected = form.querySelector('input[name="contact_pref"]:checked');
    if (!selected) return;
    localStorage.setItem(key, selected.value);
    saveBtn.classList.add('btn-success');
    setTimeout(() => saveBtn.classList.remove('btn-success'), 1000);
  });
});
