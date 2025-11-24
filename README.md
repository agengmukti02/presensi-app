# Presensi App

A modern attendance management system built with Laravel 11, Inertia.js, React, and Tailwind CSS.

## Features

-   **Role-Based Access Control**:
    -   **Admin**: Manage employees, view all attendance, approve/reject leave requests.
    -   **Pegawai**: View personal attendance, submit leave requests.
-   **Dashboard**:
    -   Real-time attendance overview.
    -   Monthly attendance table with status indicators (Hadir, Sakit, Izin, DD, DL).
    -   Search functionality for employees.
    -   Filter by month and year.
-   **Attendance Management**:
    -   Automated attendance tracking (via API or manual entry).
    -   Visual indicators for different attendance statuses.
-   **Leave Management**:
    -   Employee leave submission form.
    -   Admin approval workflow with transaction support.
    -   Automatic attendance record creation upon approval.
-   **Reporting**:
    -   Export attendance reports to Excel (`.xlsx`).
    -   Export attendance reports to PDF (`.pdf`).

## Tech Stack

-   **Backend**: Laravel 11
-   **Frontend**: React, Inertia.js
-   **Styling**: Tailwind CSS
-   **Database**: MySQL
-   **PDF Generation**: dompdf
-   **Excel Export**: Laravel Excel (Maatwebsite)

## Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/agengmukti02/presensi-app.git
    cd presensi-app
    ```

2.  **Install PHP dependencies**
    ```bash
    composer install
    ```

3.  **Install Node.js dependencies**
    ```bash
    npm install
    ```

4.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Configure your database settings in `.env`.

5.  **Run Migrations**
    ```bash
    php artisan migrate
    ```

6.  **Build Assets**
    ```bash
    npm run build
    ```

7.  **Serve Application**
    ```bash
    php artisan serve
    ```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
