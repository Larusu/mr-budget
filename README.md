<h1>
    <img src="./assets/images/piggy-bank.svg" alt="Mr. Budget logo" width="75" style="vertical-align: middle;" />
    Mr. Budget
</h1>

Mr. Budget is a personal finance management web application that helps users track their income, expenses, and savings goals. This is a final project for **Web Design and Development 1**.

## Features

- **User Authentication** - Secure login and registration system
- **Dashboard** - Overview of financial health with visual charts
- **Income Tracking** - Add, edit, delete, and view income sources
- **Expense Tracking** - Manage and categorize expenses
- **Savings Goals** - Set and track savings targets with progress bars
- **Financial Charts** - Visual comparisons of income vs expenses
- **Goal Progress** - Track progress toward savings goals

## ▶️ How to Build and Run (XAMPP)

### 📦  Prerequisites
- XAMPP installed on your system

### ⚙️ Steps

#### 1. **Start Apache and MySQL**
   - Open XAMPP Control Panel
   - Start the Apache module
   - Start the MySQL module

#### 2. **Place Project Files**
   - Navigate to `C:\xampp\htdocs\` (Windows) or `/opt/lampp/htdocs/` (Linux)
   - Create a folder named `mr-budget`
   - Copy all project files into this folder

#### 3. **Access the Application**
   - Open your browser
   - Navigate to: `http://localhost/mr-budget/`

#### 4. **Database Setup**
   - The database **`budget_db`** is automatically created on first access
   - The following tables are also created automatically:
        - users  
        - income  
        - expenses  
        - savings_goals

### 🛠️ Default Configuration

| Setting | Value |
|--------|------|
| Host | localhost |
| Database | budget_db |
| Username | root |
| Password | (empty) |
| Port | 3306 |

## 🧰 Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Charts:** Chart.js
- **Icons:** Font Awesome

## 🗂️ File Structure

```
mr-budget/
├── assets/
│   ├── auth/
│   │   ├── login.php
│   │   ├── logout.php
│   │   └── register.php
│   ├── css/
│   │   └── style.css
│   ├── images/
│   │   └── (SVG and image assets)
│   └── js/
│       └── script.js
├── charts/
│   ├── financial_comparison.php
│   └── goal_progress.php
├── config/
│   └── database.php
├── dashboard/
│   ├── index.php
│   └── profile.php
├── expenses/
│   ├── add.php
│   ├── edit.php
│   └── list.php
├── helpers/
│   └── auth.php
├── includes/
│   ├── footer.php
│   └── header.php
├── income/
│   ├── add.php
│   ├── edit.php
│   └── list.php
├── savings_goals/
│   ├── add.php
│   ├── edit.php
│   └── list.php
├── index.php
└── README.md
```

## 📝 Notes

- 🔐 The application uses **PHP sessions** for user authentication
- 🗄️ All data is stored locally in **MySQL** via **XAMPP**
- 📊 Charts are rendered using **Chart.js** library
