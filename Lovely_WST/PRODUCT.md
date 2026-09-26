# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Laravel, with Blade views and Tailwind CSS on the frontend. Must follow the Routes → Controller → Model → Database → Blade flow.
Database: SQLite, chosen because MySQL isn't installed on the dev machine. MySQL is the course's recommendation, and switching only requires changing `.env` (see README).

## Users

One person managing their own tasks. There are no teams, sharing, or roles. It is a personal tool, used on both desktop and phone (the interface must be responsive).

The project is also an individual student assignment, so a second audience is the instructor, who grades it on whether the required features work.

## Product Purpose

A simple personal task manager. The user can see what they need to do, what's done, and what's overdue. Success means every required CRUD feature works reliably. The course states that **functionality matters more than design**.

## Positioning

A deliberately small, focused task list with a clean dashboard. It makes overdue work obvious instead of hiding it in a long list.

## Operating Context

- Built within a one-week deadline (course: WST, Project Code WST21-PM-2026-SF).
- Submitted as a public GitHub repository URL. The repo must hold the complete, working Laravel project and a README.md.

## Capabilities and Constraints

Required features:
- **Add Task**: create a new task.
- **View Tasks**: display all saved tasks.
- **Edit Task**: update task information.
- **Delete Task**: remove a task.
- **Update Status**: set a task as **Pending** or **Completed**.

Added by the owner:
- **Overdue handling**: a task that is past its `due_date` and not Completed is flagged as overdue.
- **Dashboard**: a clean overview of the tasks.

`tasks` table (minimum):

| Field | Purpose |
|---|---|
| id | Task ID |
| task_name | Name of the task |
| description | Task details |
| status | Pending / Completed |
| due_date | Task deadline |

Terminology: status values are exactly "Pending" and "Completed". "Overdue" is a derived state, not a third stored status.

Out of scope unless the owner adds them: authentication and multiple users, categories or tags, collaboration.

README.md must include:
```
Project Code: WST21-PM-2026-SF
Student Name:
Course & Year:
Database Used:
Features:
- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status
```
Open: Student Name, Course & Year, and Database Used are still to be supplied by the owner. Do not invent them.

## Brand Commitments

- Name: Lovely WST (working name, from the project folder).
- The owner asked for "modern, sleek, responsive" with a "clean dashboard", and to **keep it simple**.

## Evidence on Hand

None yet. The project folder was empty at init. There are no users, data, or assets, so do not fabricate sample claims.

## Product Principles

1. **Working beats pretty.** Every required feature must work end to end before any visual extras.
2. **Keep it simple.** Build the fewest screens and concepts that cover the requirements. Readable, conventional Laravel code a student can explain.
3. **Overdue is unmissable.** Late work stands out at a glance on the dashboard and in the list.
4. **Status in one click.** Toggling Pending ↔ Completed should be the easiest action in the app.
