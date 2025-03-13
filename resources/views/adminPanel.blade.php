@extends('layouts.main')
 
 @section('content')
 <div class="container admin-panel">
    <div class="panel-header">
        <h2>Admin Panel</h2>
        <p class="panel-subtitle">Styr brugerne her</p>
    </div>
 
     @if(session('message'))
         <div class="alert alert-success">{{ session('message') }}</div>
     @endif
 
     @if(auth()->user())
     <div class="table-container">
        <div class="table-responsive">
            <table class="table admin-table">
             <thead>
                 <tr>
                     <th>Navn</th>
                     <th>Email</th>
                     <th>Afdeling</th>
                     <th>Kodeord</th>
                     <th>Handlinger</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach($users as $user)
                     <tr>
                         <form class="user-form" action="{{ route('admin.updateUser', $user->id) }}" method="POST">
                             @csrf
                             @method('PUT')
                             <td><input type="text" name="name" value="{{ $user->name }}" class="form-input"></td>
                             <td><input type="email" name="email" value="{{ $user->email }}" class="form-input"></td>
                             <td><input type="text" name="department" value="{{ $user->department }}" class="form-input"></td>
                             <td><input type="password" name="password" placeholder="Nyt Kodeord" class="form-input"></td>
                             <td class="action-buttons">
                                 <button type="submit" class="btn btn-update">Opdater</button>
                             </td>
                         </form>
                         <td>
                             <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('Er du sikker på at du vil slette denne user?')">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn btn-delete">Slet</button>
                             </form>
                         </td>
                     </tr>
                 @endforeach
             </tbody>
         </table>
        </div>
    </div>
     @else
         <p>You must be logged in to access the admin panel.</p>
     @endif
 </div>
<style>
 .admin-panel {
     padding: 2rem;
     background: #fff;
     border-radius: 15px;
     box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
     margin: 2rem auto;
 }
 
 .panel-header {
     margin-bottom: 2.5rem;
     padding-bottom: 1.5rem;
     border-bottom: 2px solid #f0f0f0;
     text-align: center;
 }
 
 .panel-header h2 {
     color: #2c3e50;
     font-size: 2.2rem;
     font-weight: 700;
     margin-bottom: 0.5rem;
 }
 
 .panel-subtitle {
     color: #7f8c8d;
     font-size: 1.1rem;
 }
 
 .table-container {
     background: #fff;
     border-radius: 10px;
     padding: 1rem;
     box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
 }
 
 .admin-table {
     width: 100%;
     border-collapse: separate;
     border-spacing: 0;
     margin: 1rem 0;
 }
 
 .admin-table th {
     background: #f8fafc;
     padding: 1.2rem 1rem;
     font-weight: 600;
     text-align: left;
     color: #2c3e50;
     border-bottom: 2px solid #edf2f7;
     font-size: 0.95rem;
     text-transform: uppercase;
     letter-spacing: 0.5px;
 }
 
 .admin-table td {
     padding: 1rem;
     vertical-align: middle;
     border-bottom: 1px solid #edf2f7;
     transition: background-color 0.2s;
 }
 
 .admin-table tr:hover td {
     background-color: #f8fafc;
 }
 
 .form-input {
     width: 100%;
     padding: 0.7rem;
     border: 1px solid #e2e8f0;
     border-radius: 6px;
     transition: all 0.3s;
     font-size: 0.95rem;
 }
 
 .form-input:focus {
     border-color: #4299e1;
     box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
     outline: none;
 }
 
 .action-buttons {
     display: flex;
     gap: 0.8rem;
 }
 
 .btn {
     padding: 0.6rem 1.2rem;
     border: none;
     border-radius: 6px;
     cursor: pointer;
     font-weight: 500;
     transition: all 0.2s;
     display: flex;
     align-items: center;
     gap: 0.5rem;
     font-size: 0.9rem;
 }
 
 .btn-update {
     background: #3498db;
     color: white;
 }
 
 .btn-update:hover {
     background: #2980b9;
     transform: translateY(-1px);
 }
 
 .btn-delete {
     background: #e74c3c;
     color: white;
 }
 
 .btn-delete:hover {
     background: #c0392b;
     transform: translateY(-1px);
 }
 
 /* Modal Styles */
 .modal {
     display: none;
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background: rgba(0, 0, 0, 0.5);
     z-index: 1000;
     align-items: center;
     justify-content: center;
 }
 
 .modal.show {
     display: flex !important;
 }
 
 .modal-content {
     background: #fff;
     width: 90%;
     max-width: 500px;
     border-radius: 12px;
     box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
     position: relative;
     margin: 0 auto;
 }
 
 .modal-header {
     padding: 1.5rem;
     border-bottom: 1px solid #edf2f7;
 }
 
 .modal-header h3 {
     margin: 0;
     color: #2c3e50;
     font-size: 1.5rem;
     font-weight: 600;
 }
 
 .modal-body {
     padding: 2rem;
     color: #4a5568;
     font-size: 1.1rem;
     line-height: 1.6;
 }
 
 .modal-footer {
     padding: 1.5rem;
     border-top: 1px solid #edf2f7;
     text-align: right;
 }
 
 #modalButtons {
     display: flex;
     gap: 1rem;
     justify-content: flex-end;
 }
 
 .modal.success .modal-header {
     border-bottom: 4px solid #2ecc71;
 }
 
 .modal.error .modal-header {
     border-bottom: 4px solid #e74c3c;
 }
 
 .modal.warning .modal-header {
     border-bottom: 4px solid #f39c12;
 }
 
 .access-denied {
     text-align: center;
     padding: 3rem;
     background: #f8fafc;
     border-radius: 12px;
     color: #e74c3c;
     margin: 2rem 0;
 }
 
 .access-denied i {
     font-size: 3rem;
     margin-bottom: 1rem;
 }
 
 @media (max-width: 768px) {
     .admin-panel {
         padding: 1rem;
     }
     
     .action-buttons {
         flex-direction: column;
     }
     
     .form-input {
         font-size: 0.9rem;
     }
 }
 
 /* Fjern pointer-events fra modal baggrunden */
 .modal::before {
     content: '';
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     pointer-events: none;
 }
 
 /* G r X knappen usynlig eller fjern den helt */
 .modal-close {
     display: none;
 }
 
 .notification {
     position: fixed;
     top: 20px;
     right: 20px;
     padding: 1rem 2rem;
     border-radius: 8px;
     display: flex;
     align-items: center;
     gap: 1rem;
     z-index: 1000;
     transition: all 0.3s ease;
     opacity: 0;
     transform: translateY(-20px);
     box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
 }
 
 .notification.show {
     opacity: 1;
     transform: translateY(0);
 }
 
 .notification.hidden {
     display: none;
 }
 
 .notification.success {
     background: #10B981;
     color: white;
 }
 
 .notification.error {
     background: #EF4444;
     color: white;
 }
 
 .notification-message {
     font-size: 1rem;
     font-weight: 500;
 }
 
 .close-btn {
     background: none;
     border: none;
     color: white;
     font-size: 1.2rem;
     cursor: pointer;
     padding: 0;
     opacity: 0.8;
     transition: opacity 0.2s;
 }
 
 .close-btn:hover {
     opacity: 1;
 }
 </style>
 @endsection