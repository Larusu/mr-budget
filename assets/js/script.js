function togglePassword(button) {
  const input = button.previousElementSibling;
  const icon = button.querySelector('i');

  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
    icon.setAttribute('title', 'Hide password');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
    icon.setAttribute('title', 'Show password');
  }
}


function validatePasswords() {
  const password = document.getElementById('password').value;
  const confirm = document.getElementById('confirm_password').value;
  const errorText = document.getElementById('password-error');

  if (password !== confirm) {
    errorText.classList.add('visible');
    return false;
  }

  errorText.classList.remove('visible');
  return true;
}

function toggleForm() {
  const form = document.getElementById("collapsibleForm");

  if (form.classList.contains("open")) {
    form.style.maxHeight = 0;
    form.classList.remove("open");
  } else {
    form.classList.add("open");

    // Force reflow to ensure scrollHeight is accurate
    void form.offsetHeight;

    form.style.maxHeight = form.scrollHeight + "px";
  }
}

function openEditModal(button) {
  const id = button.getAttribute('data-id');
  const source = button.getAttribute('data-source');
  const amount = button.getAttribute('data-amount');
  const date = button.getAttribute('data-date');

  document.getElementById('edit-id').value = id;
  document.getElementById('edit-source').value = source;
  document.getElementById('edit-amount').value = amount;
  document.getElementById('edit-date').value = date;

  document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
  document.getElementById('editModal').style.display = 'none';
}

function openExpenseEditModal(button) {
  const id = button.getAttribute('data-id');
  const category = button.getAttribute('data-category');
  const description = button.getAttribute('data-description');
  const amount = button.getAttribute('data-amount');
  const date = button.getAttribute('data-date');

  document.getElementById('edit-expense-id').value = id;
  document.getElementById('edit-expense-category').value = category;
  document.getElementById('edit-expense-description').value = description;
  document.getElementById('edit-expense-amount').value = amount;
  document.getElementById('edit-expense-date').value = date;
  document.getElementById('editExpenseModal').style.display = 'flex';
}


function closeExpenseEditModal() {
  document.getElementById('editExpenseModal').style.display = 'none';
}

window.onclick = function(event) {
  const incomeModal = document.getElementById('editModal');
  const expenseModal = document.getElementById('editExpenseModal');

  if (event.target === incomeModal) {
    closeEditModal();
  } else if (event.target === expenseModal) {
    closeExpenseEditModal();
  }
}


function openGoalEditModal(btn) {
    document.getElementById('edit-goal-id').value = btn.dataset.id;
    document.getElementById('edit-goal-name').value = btn.dataset.goal;
    document.getElementById('edit-goal-target').value = btn.dataset.target;
    document.getElementById('edit-goal-saved').value = btn.dataset.saved;
    document.getElementById('edit-goal-start').value = btn.dataset.start;
    document.getElementById('edit-goal-end').value = btn.dataset.end;
    document.getElementById('editGoalModal').style.display = 'flex';
}

function closeGoalEditModal() {
    document.getElementById('editGoalModal').style.display = 'none';
}

//Pie chart for financial comparison
document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("pieChart");
  const ctx = canvas?.getContext("2d");

  if (ctx) {
    // Create gradient for each slice
    const incomeGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    incomeGradient.addColorStop(0, "#111");
    incomeGradient.addColorStop(1, "#333");

    const expenseGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    expenseGradient.addColorStop(0, "#111");
    expenseGradient.addColorStop(1, "#333");

    new Chart(ctx, {
      type: "pie",
      data: {
        labels: ["Income", "Expenses"],
        datasets: [{
          label: "Income vs Expenses",
          data: [totalIncome, totalExpenses],
          backgroundColor: [incomeGradient, expenseGradient],
          borderColor: ["#777", "#555"],
          borderWidth: 2,
          hoverOffset: -10
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              color: "#fff"
            }
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                let label = context.label || '';
                let value = context.raw || 0;
                return `${label}: ₱${value.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
              }
            }
          }
        }
      }
    });
  }
});

const statusEl = document.getElementById("budgetStatus");

if (statusEl) {
  const difference = totalIncome - totalExpenses;

  if (difference > 0) {
    statusEl.textContent = "You are under budget";
    statusEl.style.color = "#ffffffff"; 
  } else if (difference < 0) {
    statusEl.textContent = "You are over budget";
    statusEl.style.color = "#ffffffff"; 
  } else {
    statusEl.textContent = "Budget balanced";
    statusEl.style.color = "#ffffffff"; 
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("dashboardPieChart");
  const ctx = canvas?.getContext("2d");

  if (ctx) {
    // Create gradient for each slice
    const incomeGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    incomeGradient.addColorStop(0, "#111");
    incomeGradient.addColorStop(1, "#333");

    const expenseGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    expenseGradient.addColorStop(0, "#111");
    expenseGradient.addColorStop(1, "#333");

    new Chart(ctx, {
      type: "pie",
      data: {
        labels: ["Income", "Expenses"],
        datasets: [{
          label: "Income vs Expenses",
          data: [totalIncome, totalExpenses],
          backgroundColor: [incomeGradient, expenseGradient],
          borderColor: ["#777", "#555"],
          borderWidth: 2,
          hoverOffset: -10
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              color: "#fff"
            }
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                let label = context.label || '';
                let value = context.raw || 0;
                return `${label}: ₱${value.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
              }
            }
          }
        }
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("lineChart");
  const ctx = canvas?.getContext("2d");

  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartMonths,
        datasets: [
          {
            label: 'Income',
            data: chartIncomes,
            borderColor: '#4caf50',
            backgroundColor: 'rgba(76, 175, 80, 0.2)',
            tension: 0.3
          },
          {
            label: 'Expenses',
            data: chartExpenses,
            borderColor: '#f44336',
            backgroundColor: 'rgba(244, 67, 54, 0.2)',
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            labels: {
              color: '#fff'
            }
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                return `${context.dataset.label}: ₱${context.raw.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
              }
            }
          }
        },
        scales: {
          x: {
            ticks: { color: '#aaa' },
            grid: { color: '#333' }
          },
          y: {
            ticks: { color: '#aaa' },
            grid: { color: '#333' }
          }
        }
      }
    });
  }
});



















