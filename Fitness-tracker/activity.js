// Select elements
const addBtn = document.getElementById('addActivityBtn');
const stepsInput = document.getElementById('stepsInput');
const caloriesInput = document.getElementById('caloriesInput');
const timeInput = document.getElementById('timeInput');
const historyDiv = document.getElementById('activityHistory');

// Event listener for adding activity
addBtn.addEventListener('click', () => {
  const steps = stepsInput.value;
  const calories = caloriesInput.value;
  const time = timeInput.value;

  if (!steps || !calories || !time) {
    alert("Please fill in all fields!");
    return;
  }

  // Create new card
  const newCard = document.createElement('div');
  newCard.classList.add('col-md-6');
  newCard.innerHTML = `
    <div class="card history-card p-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between">
        <div>
          <i class="fa-solid fa-shoe-prints text-success me-2"></i>
          Steps: <span class="text-success fw-bold">${steps}</span>
        </div>
        <span class="text-muted">Now</span>
      </div>
      <p class="mb-1 mt-2">Calories: ${calories}</p>
      <p class="text-muted">Time: ${time}</p>
    </div>
  `;

  // Prepend so latest activity appears on top
  historyDiv.prepend(newCard);

  // Clear inputs after adding
  stepsInput.value = '';
  caloriesInput.value = '';
  timeInput.value = '';
});