<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index");
    exit;
}

$fullname = $_SESSION["fullname"] ?? "User";
$email    = $_SESSION["email"] ?? "";
$role     = $_SESSION["role"] ?? "student";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Study Groups · TutorLink</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
    }

    html,
    body {
      background: #ffffff;
      height: 100%;
    }

    body {
      color: #0a2e3f;
      overflow: hidden;
    }

    .wrap {
      display: flex;
      height: 100vh;
      background: #ffffff;
    }

    .list-pane {
      width: 340px;
      border-right: 1px solid #e4f0ea;
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      background: #ffffff;
    }

    .list-header {
      padding: 1rem 1.25rem 0.85rem;
      border-bottom: 1px solid #e4f0ea;
    }

    .list-header-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 0.75rem;
    }

    .list-header h1 {
      font-size: 1.05rem;
      font-weight: 700;
      color: #0a4b6e;
    }

    .list-header span {
      font-size: 0.72rem;
      color: #6b8b7e;
      font-weight: 600;
    }

    .search-wrap {
      position: relative;
    }

    .search-wrap i {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      color: #8a9e94;
      font-size: 0.78rem;
      pointer-events: none;
    }

    .search-input {
      width: 100%;
      padding: 0.55rem 0.75rem 0.55rem 2rem;
      font-size: 0.83rem;
      border: 1px solid #cfe3da;
      border-radius: 5px;
      outline: none;
      color: #0a2e3f;
      background: #ffffff;
      font-family: inherit;
    }

    .search-input::placeholder {
      color: #a8bcb2;
    }

    .search-input:focus {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
    }

    .create-wrap {
      padding: 0.85rem 1.25rem;
      border-bottom: 1px solid #e4f0ea;
    }

    .btn-create {
      width: 100%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.7rem 1rem;
      border-radius: 5px;
      border: 1.5px solid #0b6e4f;
      background: #ffffff;
      color: #0b6e4f;
      font-size: 0.86rem;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
    }

    .btn-create:hover {
      background: #f0f7f4;
    }

    .list-scroll {
      flex: 1;
      overflow-y: auto;
    }

    .group-row {
      display: flex;
      gap: 0.75rem;
      padding: 0.95rem 1.25rem;
      border-bottom: 1px solid #f0f7f4;
      cursor: pointer;
      border-left: 3px solid transparent;
    }

    .group-row:hover {
      background: #f6fbf8;
    }

    .group-row.active {
      background: #f0f7f4;
      border-left-color: #0b6e4f;
    }

    .group-avatar {
      width: 42px;
      height: 42px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      font-weight: 700;
      color: #ffffff;
      flex-shrink: 0;
    }

    .group-body {
      flex: 1;
      min-width: 0;
    }

    .group-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
      margin-bottom: 0.2rem;
    }

    .group-name {
      font-size: 0.88rem;
      font-weight: 600;
      color: #0a4b6e;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .group-count {
      font-size: 0.68rem;
      color: #6b8b7e;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
    }

    .group-topic {
      font-size: 0.78rem;
      color: #6b8b7e;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .empty-list {
      padding: 3rem 1.25rem;
      text-align: center;
      color: #8a9e94;
      font-size: 0.85rem;
      line-height: 1.5;
    }

    .empty-list i {
      display: block;
      font-size: 2rem;
      color: #cfe3da;
      margin-bottom: 0.65rem;
    }

    .empty-list strong {
      color: #0a4b6e;
      font-weight: 600;
    }

    .detail-pane {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: #ffffff;
      min-width: 0;
    }

    .detail-header {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #e4f0ea;
      display: flex;
      align-items: center;
      gap: 0.85rem;
    }

    .detail-back {
      display: none;
      width: 34px;
      height: 34px;
      border-radius: 5px;
      border: 1px solid #cfe3da;
      background: #ffffff;
      color: #0a4b6e;
      cursor: pointer;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      flex-shrink: 0;
    }

    .detail-back:hover {
      background: #f0f7f4;
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .detail-avatar {
      width: 42px;
      height: 42px;
      border-radius: 5px;
      display: none;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      font-weight: 700;
      color: #ffffff;
      flex-shrink: 0;
    }

    .detail-header-text {
      min-width: 0;
      flex: 1;
    }

    .detail-title {
      font-size: 1rem;
      font-weight: 700;
      color: #0a4b6e;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 0.1rem;
    }

    .detail-sub {
      font-size: 0.72rem;
      color: #6b8b7e;
      font-weight: 500;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      flex-shrink: 0;
    }

    .detail-action {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.55rem 0.95rem;
      border-radius: 5px;
      font-size: 0.82rem;
      font-weight: 600;
      cursor: pointer;
      border: 1.5px solid #0b6e4f;
      background: #ffffff;
      color: #0b6e4f;
      font-family: inherit;
      flex-shrink: 0;
    }

    .detail-action:hover {
      background: #f0f7f4;
    }

    .detail-action.joined {
      border-color: #cfe3da;
      color: #6b8b7e;
      background: #f6fbf8;
    }

    .detail-action.joined:hover {
      border-color: #c0392b;
      color: #c0392b;
      background: #fdecea;
    }

    .header-icon-btn {
      width: 34px;
      height: 34px;
      border-radius: 5px;
      border: 1px solid #cfe3da;
      background: #ffffff;
      color: #0a4b6e;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.82rem;
    }

    .header-icon-btn:hover {
      background: #f0f7f4;
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .header-icon-btn.danger:hover {
      background: #fdecea;
      border-color: #c0392b;
      color: #c0392b;
    }

    .tabs {
      display: none;
      border-bottom: 1px solid #e4f0ea;
      background: #ffffff;
      padding: 0 1.5rem;
      gap: 0.25rem;
    }

    .tab {
      padding: 0.85rem 0.85rem;
      font-size: 0.85rem;
      font-weight: 600;
      color: #6b8b7e;
      background: transparent;
      border: none;
      border-bottom: 2px solid transparent;
      cursor: pointer;
      font-family: inherit;
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
    }

    .tab:hover {
      color: #0b6e4f;
    }

    .tab.active {
      color: #0b6e4f;
      border-bottom-color: #0b6e4f;
    }

    .tab-count {
      font-size: 0.68rem;
      font-weight: 700;
      padding: 0.1rem 0.4rem;
      border-radius: 8px;
      background: #f0f7f4;
      color: #0b6e4f;
      margin-left: 0.15rem;
    }

    .tab-content {
      flex: 1;
      min-height: 0;
      display: none;
      flex-direction: column;
    }

    .tab-content.active {
      display: flex;
    }

    .announcement-bar {
      display: flex;
      gap: 0.5rem;
      padding: 0.6rem 1rem;
      border-bottom: 1px solid #e4f0ea;
      background: #f6fbf8;
      align-items: center;
    }

    .announcement-bar input {
      flex: 1;
      padding: 0.55rem 0.8rem;
      border: 1px solid #cfe3da;
      border-radius: 5px;
      font-size: 0.83rem;
      font-family: inherit;
      outline: none;
      color: #0a2e3f;
      background: #ffffff;
    }

    .announcement-bar input:focus {
      border-color: #0b6e4f;
    }

    .announcement-bar button {
      padding: 0.55rem 0.9rem;
      border: 1.5px solid #0b6e4f;
      background: #ffffff;
      color: #0b6e4f;
      border-radius: 5px;
      font-size: 0.8rem;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
    }

    .announcement-bar button:hover {
      background: #f0f7f4;
    }

  .chat-scroll {
  flex: 1;
  overflow-y: auto;
  padding: 1.25rem 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  position: relative;
}
  .chat-announcement {
  position: sticky;
  top: 0;
  z-index: 5;
  border: 1px solid #cfe3da;
  border-radius: 5px;
  padding: 0.85rem 1rem;
  background: #f6fbf8;
  margin-bottom: 1.75rem;
  box-shadow: 0 4px 10px -6px rgba(11, 110, 79, 0.15);
}

    .chat-announcement-label {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #0b6e4f;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      margin-bottom: 0.35rem;
    }

    .chat-announcement-text {
      font-size: 0.83rem;
      color: #0a2e3f;
      line-height: 1.5;
    }

    .msg {
      display: flex;
      gap: 0.6rem;
      max-width: 78%;
    }

    .msg.own {
      align-self: flex-end;
      flex-direction: row-reverse;
    }

    .msg-avatar {
      width: 32px;
      height: 32px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.72rem;
      font-weight: 700;
      color: #ffffff;
      flex-shrink: 0;
    }

    .msg-bubble {
      background: #f6fbf8;
      border: 1px solid #e4f0ea;
      border-radius: 8px;
      padding: 0.55rem 0.75rem;
      min-width: 0;
    }

    .msg.own .msg-bubble {
      background: #f0f7f4;
      border-color: #cfe3da;
    }

    .msg-meta {
      display: flex;
      align-items: baseline;
      gap: 0.5rem;
      margin-bottom: 0.2rem;
    }

    .msg-name {
      font-size: 0.75rem;
      font-weight: 600;
      color: #0a4b6e;
    }

    .msg-time {
      font-size: 0.65rem;
      color: #8a9e94;
      font-weight: 500;
    }

    .msg-text {
      font-size: 0.86rem;
      color: #0a2e3f;
      line-height: 1.5;
      white-space: pre-wrap;
      word-wrap: break-word;
    }

    .chat-empty {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem 1.5rem;
      text-align: center;
      color: #8a9e94;
      font-size: 0.85rem;
      line-height: 1.5;
    }

    .chat-empty i {
      font-size: 2rem;
      color: #cfe3da;
      margin-bottom: 0.65rem;
    }

    .chat-empty strong {
      color: #0a4b6e;
      font-weight: 600;
    }

    .chat-input-bar {
      border-top: 1px solid #e4f0ea;
      padding: 0.75rem 1rem;
      display: flex;
      gap: 0.5rem;
      background: #ffffff;
      flex-shrink: 0;
    }

    .chat-input {
      flex: 1;
      padding: 0.7rem 0.9rem;
      border: 1.5px solid #cfe3da;
      border-radius: 5px;
      font-size: 0.88rem;
      outline: none;
      font-family: inherit;
      color: #0a2e3f;
      resize: none;
      max-height: 120px;
      min-height: 40px;
      line-height: 1.4;
    }

    .chat-input:focus {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
    }

    .chat-send {
      width: 42px;
      height: 42px;
      border-radius: 5px;
      border: 1.5px solid #0b6e4f;
      background: #ffffff;
      color: #0b6e4f;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      flex-shrink: 0;
      align-self: flex-end;
    }

    .chat-send:hover {
      background: #f0f7f4;
    }

    .chat-send:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .resources-scroll {
      flex: 1;
      overflow-y: auto;
      padding: 1.25rem 1.5rem;
    }

    .resource-add {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .resource-add input {
      flex: 1;
      padding: 0.7rem 0.9rem;
      border: 1.5px solid #cfe3da;
      border-radius: 5px;
      font-size: 0.86rem;
      outline: none;
      font-family: inherit;
      color: #0a2e3f;
    }

    .resource-add input:focus {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
    }

    .resource-add button {
      padding: 0 1rem;
      border: 1.5px solid #0b6e4f;
      background: #ffffff;
      color: #0b6e4f;
      border-radius: 5px;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }

    .resource-add button:hover {
      background: #f0f7f4;
    }

    .resource-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .resource-item {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      padding: 0.7rem 0.85rem;
      border: 1px solid #e4f0ea;
      border-radius: 5px;
    }

    .resource-icon {
      width: 34px;
      height: 34px;
      border-radius: 5px;
      border: 1px solid #cfe3da;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #0b6e4f;
      font-size: 0.85rem;
      flex-shrink: 0;
    }

    .resource-info {
      flex: 1;
      min-width: 0;
    }

    .resource-link {
      font-size: 0.85rem;
      font-weight: 600;
      color: #0a4b6e;
      text-decoration: none;
      display: block;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .resource-link:hover {
      color: #0b6e4f;
      text-decoration: underline;
    }

    .resource-meta {
      font-size: 0.7rem;
      color: #8a9e94;
      margin-top: 0.15rem;
    }

    .resource-delete {
      width: 30px;
      height: 30px;
      border-radius: 5px;
      border: 1px solid transparent;
      background: transparent;
      color: #8a9e94;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      flex-shrink: 0;
    }

    .resource-delete:hover {
      background: #fdecea;
      border-color: #f5c6c1;
      color: #c0392b;
    }

    .members-scroll {
      flex: 1;
      overflow-y: auto;
      padding: 1.25rem 1.5rem;
    }

    .member-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .member-item {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      padding: 0.7rem 0.85rem;
      border: 1px solid #e4f0ea;
      border-radius: 5px;
    }

    .member-avatar {
      width: 34px;
      height: 34px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      font-weight: 700;
      color: #ffffff;
      flex-shrink: 0;
    }

    .member-name {
      font-size: 0.88rem;
      font-weight: 500;
      color: #0a2e3f;
      flex: 1;
      min-width: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .member-role {
      font-size: 0.68rem;
      font-weight: 700;
      color: #6b8b7e;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 0.2rem 0.5rem;
      border-radius: 5px;
      border: 1px solid #e4f0ea;
      flex-shrink: 0;
    }

    .member-role.owner {
      color: #0b6e4f;
      border-color: #cfe3da;
    }

    .member-role.admin {
      color: #0a4b6e;
      border-color: #cfe3da;
    }

    .member-actions {
      display: flex;
      gap: 0.3rem;
      flex-shrink: 0;
    }

    .member-action-btn {
      width: 28px;
      height: 28px;
      border-radius: 5px;
      border: 1px solid #cfe3da;
      background: #ffffff;
      color: #0a4b6e;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
    }

    .member-action-btn:hover {
      background: #f0f7f4;
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .member-action-btn.danger:hover {
      background: #fdecea;
      border-color: #c0392b;
      color: #c0392b;
    }

    .detail-empty {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 1.5rem;
      text-align: center;
      color: #8a9e94;
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .detail-empty i {
      font-size: 2.5rem;
      color: #cfe3da;
      margin-bottom: 0.85rem;
    }

    .detail-empty strong {
      color: #0a4b6e;
      font-weight: 600;
    }

    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(10, 46, 63, 0.45);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 999;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.2s ease;
      padding: 1rem;
    }

    .modal-overlay.show {
      opacity: 1;
      pointer-events: auto;
    }

    .modal {
      background: #ffffff;
      border: 1px solid #cfe3da;
      border-radius: 5px;
      width: 100%;
      max-width: 460px;
      max-height: 90vh;
      overflow-y: auto;
      padding: 1.5rem;
      box-shadow: 0 25px 50px -20px rgba(11, 110, 79, 0.3);
      transform: translateY(-10px);
      transition: transform 0.2s ease;
    }

    .modal-overlay.show .modal {
      transform: translateY(0);
    }

    .modal-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1.25rem;
    }

    .modal-icon {
      width: 42px;
      height: 42px;
      border-radius: 5px;
      border: 1px solid #cfe3da;
      color: #0b6e4f;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }

    .modal-icon.danger {
      color: #c0392b;
      border-color: #f5c6c1;
    }

    .modal-header h3 {
      font-size: 1.05rem;
      font-weight: 700;
      color: #0a4b6e;
    }

    .modal-header p {
      font-size: 0.78rem;
      color: #6b8b7e;
      margin-top: 0.15rem;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .input-label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: #0a4b6e;
      margin-bottom: 0.35rem;
    }

    .input-field,
    .textarea-field {
      width: 100%;
      padding: 0.7rem 0.85rem;
      font-size: 0.88rem;
      background: #ffffff;
      border: 1.5px solid #cfe3da;
      border-radius: 5px;
      outline: none;
      color: #0a2e3f;
      font-family: inherit;
    }

    .textarea-field {
      resize: vertical;
      min-height: 90px;
    }

    .input-field:focus,
    .textarea-field:focus {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
    }

    .input-field.error,
    .textarea-field.error {
      border-color: #c0392b;
      box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.08);
    }

    .error-message {
      display: none;
      color: #c0392b;
      font-size: 0.75rem;
      font-weight: 500;
      margin-top: 0.3rem;
    }

    .error-message.show {
      display: block;
    }

    .modal-actions {
      display: flex;
      gap: 0.6rem;
      justify-content: flex-end;
      margin-top: 1.25rem;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.65rem 1.1rem;
      border-radius: 5px;
      font-size: 0.86rem;
      font-weight: 600;
      cursor: pointer;
      border: 1.5px solid #0a4b6e;
      background: #ffffff;
      color: #0a4b6e;
      font-family: inherit;
    }

    .btn:hover {
      background: #f0f7f4;
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .btn-primary {
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .btn-primary:hover {
      background: #f0f7f4;
    }

    .btn-danger {
      border-color: #c0392b;
      color: #c0392b;
    }

    .btn-danger:hover {
      background: #fdecea;
      border-color: #c0392b;
      color: #c0392b;
    }

    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .toast {
      position: fixed;
      top: 1.5rem;
      left: 1.5rem;
      transform: translateX(-150%);
      background: #ffffff;
      padding: 1rem 1.4rem;
      border-radius: 5px;
      font-size: 0.9rem;
      font-weight: 500;
      box-shadow: 0 15px 35px -12px rgba(10, 75, 110, 0.25);
      transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
      z-index: 1200;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      min-width: 260px;
      max-width: 340px;
      border-left: 5px solid #0b6e4f;
    }

    .toast.success {
      border-left-color: #0b6e4f;
    }

    .toast.error {
      border-left-color: #c0392b;
    }

    .toast.show {
      transform: translateX(0);
    }

    .toast-icon {
      width: 22px;
      height: 22px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
      color: #ffffff;
      flex-shrink: 0;
    }

    .toast.success .toast-icon {
      background: #0b6e4f;
    }

    .toast.error .toast-icon {
      background: #c0392b;
    }

    .toast-message {
      color: #0a2e3f;
      line-height: 1.3;
    }

    @media (max-width: 800px) {
      .list-pane {
        width: 100%;
        border-right: none;
      }

      .detail-pane {
        position: fixed;
        inset: 0;
        z-index: 50;
        transform: translateX(100%);
        transition: transform 0.3s ease;
      }

      .wrap.mobile-view-detail .detail-pane {
        transform: translateX(0);
      }

      .wrap.mobile-view-detail .list-pane {
        display: none;
      }

      .detail-back {
        display: inline-flex;
      }

      .detail-action {
        padding: 0.5rem 0.7rem;
        font-size: 0.76rem;
      }

      .toast {
        top: auto;
        bottom: 80px;
        left: 1rem;
        right: 1rem;
        min-width: auto;
        max-width: none;
      }

      .msg {
        max-width: 88%;
      }

      .tabs {
        padding: 0 0.75rem;
      }

      .tab {
        padding: 0.75rem 0.6rem;
        font-size: 0.78rem;
      }

      .tab-count {
        display: none;
      }
    }
  </style>
</head>

<body>
  <div class="wrap" id="wrap">
    <aside class="list-pane">
      <div class="list-header">
        <div class="list-header-top">
          <h1>Study Groups</h1>
          <span id="groupCountLabel">0 groups</span>
        </div>
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search groups..." autocomplete="off">
        </div>
      </div>

      <div class="create-wrap">
        <button class="btn-create" id="createGroupBtn">
          <i class="fa-solid fa-plus"></i>
          Create group
        </button>
      </div>

      <div class="list-scroll" id="listScroll"></div>
    </aside>

    <section class="detail-pane">
      <div class="detail-header">
        <button class="detail-back" id="detailBack">
          <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div class="detail-avatar" id="detailAvatar">G</div>
        <div class="detail-header-text">
          <div class="detail-title" id="detailTitle">Select a group</div>
          <div class="detail-sub" id="detailSub">Choose one from the list to open it</div>
        </div>
      </div>

      <div class="detail-empty" id="detailEmpty">
        <i class="fa-solid fa-users"></i>
        <strong>No group selected</strong>
        Pick a study group on the left, or create a new one to start.
      </div>

      <div class="tabs" id="tabs">
        <button class="tab active" data-tab="chat">
          <i class="fa-solid fa-comments"></i>
          Chat
        </button>
        <button class="tab" data-tab="resources">
          <i class="fa-solid fa-link"></i>
          Resources
          <span class="tab-count" id="resourcesCount">0</span>
        </button>
        <button class="tab" data-tab="members">
          <i class="fa-solid fa-user-group"></i>
          Members
          <span class="tab-count" id="membersCount">0</span>
        </button>
      </div>

      <div class="tab-content active" id="tab-chat">
        <div class="chat-scroll" id="chatScroll"></div>
        <div class="chat-input-bar" id="chatInputBar" style="display:none;">
          <textarea class="chat-input" id="chatInput" placeholder="Write a message..." rows="1"></textarea>
          <button class="chat-send" id="chatSend" title="Send">
            <i class="fa-solid fa-paper-plane"></i>
          </button>
        </div>
      </div>

      <div class="tab-content" id="tab-resources">
        <div class="resources-scroll">
          <div class="resource-add" id="resourceAddBar" style="display:none;">
            <input type="url" id="resourceInput" placeholder="Paste a link (https://...)">
            <button id="resourceAddBtn">
              <i class="fa-solid fa-plus"></i>
              Add
            </button>
          </div>
          <ul class="resource-list" id="resourceList"></ul>
        </div>
      </div>

      <div class="tab-content" id="tab-members">
        <div class="members-scroll">
          <ul class="member-list" id="memberList"></ul>
        </div>
      </div>
    </section>
  </div>

  <div class="modal-overlay" id="groupModal">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-icon" id="groupModalIcon">
          <i class="fa-solid fa-users"></i>
        </div>
        <div>
          <h3 id="groupModalTitle">Create study group</h3>
          <p id="groupModalSub">Start a community around a topic you're learning.</p>
        </div>
      </div>

      <div class="form-group">
        <label class="input-label" for="groupName">Group name</label>
        <input class="input-field" type="text" id="groupName" placeholder="e.g. Calculus Study Circle">
        <div class="error-message" id="groupNameError">Please enter a group name</div>
      </div>

      <div class="form-group">
        <label class="input-label" for="groupTopic">Topic</label>
        <input class="input-field" type="text" id="groupTopic" placeholder="e.g. Mathematics · Calculus">
        <div class="error-message" id="groupTopicError">Please enter a topic</div>
      </div>

      <div class="form-group">
        <label class="input-label" for="groupDescription">Description</label>
        <textarea class="textarea-field" id="groupDescription" placeholder="What is this group about? Who should join?"></textarea>
        <div class="error-message" id="groupDescriptionError">Please add a short description</div>
      </div>

      <div class="modal-actions">
        <button class="btn" id="cancelGroupModal">Cancel</button>
        <button class="btn btn-primary" id="confirmGroupModal">
          <i class="fa-solid fa-plus"></i>
          Create group
        </button>
      </div>
    </div>
  </div>

  <div class="modal-overlay" id="deleteModal">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-icon danger">
          <i class="fa-solid fa-trash"></i>
        </div>
        <div>
          <h3>Delete group?</h3>
          <p>This will permanently remove the group, its members, messages, and resources.</p>
        </div>
      </div>
      <div class="modal-actions">
        <button class="btn" id="cancelDeleteModal">Cancel</button>
        <button class="btn btn-danger" id="confirmDeleteModal">
          <i class="fa-solid fa-trash"></i>
          Delete
        </button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast">
    <div class="toast-icon" id="toastIcon">!</div>
    <div class="toast-message" id="toastMessage"></div>
  </div>

  <script>
    const API = "insert.php";

    const T = {
      groups:    "user_b6a3d990_rea_study_groups",
      members:   "user_b6a3d990_rea_group_members",
      messages:  "user_b6a3d990_rea_group_messages",
      resources: "user_b6a3d990_rea_group_resources"
    };

    const POLL_MS = 3000;

    const CURRENT_USER = {
      name: <?php echo json_encode($fullname); ?>,
      email: <?php echo json_encode($email); ?>
    };

    const wrap = document.getElementById("wrap");
    const listScroll = document.getElementById("listScroll");
    const searchInput = document.getElementById("searchInput");
    const groupCountLabel = document.getElementById("groupCountLabel");
    const detailBack = document.getElementById("detailBack");
    const detailAvatar = document.getElementById("detailAvatar");
    const detailTitle = document.getElementById("detailTitle");
    const detailSub = document.getElementById("detailSub");
    const detailEmpty = document.getElementById("detailEmpty");
    const tabs = document.getElementById("tabs");
    const tabButtons = document.querySelectorAll(".tab");
    const tabChat = document.getElementById("tab-chat");
    const tabResources = document.getElementById("tab-resources");
    const tabMembers = document.getElementById("tab-members");
    const chatScroll = document.getElementById("chatScroll");
    const chatInputBar = document.getElementById("chatInputBar");
    const chatInput = document.getElementById("chatInput");
    const chatSend = document.getElementById("chatSend");
    const resourceAddBar = document.getElementById("resourceAddBar");
    const resourceInput = document.getElementById("resourceInput");
    const resourceAddBtn = document.getElementById("resourceAddBtn");
    const resourceList = document.getElementById("resourceList");
    const resourcesCount = document.getElementById("resourcesCount");
    const memberList = document.getElementById("memberList");
    const membersCount = document.getElementById("membersCount");
    const createGroupBtn = document.getElementById("createGroupBtn");

    const groupModal = document.getElementById("groupModal");
    const groupModalTitle = document.getElementById("groupModalTitle");
    const groupModalSub = document.getElementById("groupModalSub");
    const groupModalIcon = document.getElementById("groupModalIcon");
    const cancelGroupModal = document.getElementById("cancelGroupModal");
    const confirmGroupModal = document.getElementById("confirmGroupModal");
    const groupNameInput = document.getElementById("groupName");
    const groupTopicInput = document.getElementById("groupTopic");
    const groupDescriptionInput = document.getElementById("groupDescription");
    const groupNameError = document.getElementById("groupNameError");
    const groupTopicError = document.getElementById("groupTopicError");
    const groupDescriptionError = document.getElementById("groupDescriptionError");

    const deleteModal = document.getElementById("deleteModal");
    const cancelDeleteModal = document.getElementById("cancelDeleteModal");
    const confirmDeleteModal = document.getElementById("confirmDeleteModal");

    const toast = document.getElementById("toast");
    const toastIcon = document.getElementById("toastIcon");
    const toastMessage = document.getElementById("toastMessage");

    let groups = [];
    let activeGroupId = null;
    let activeTab = "chat";
    let toastTimer = null;
    let pollTimer = null;
    let editingGroupId = null;
    let deletingGroupId = null;

    function showToast(message, type) {
      clearTimeout(toastTimer);
      toast.classList.remove("success", "error", "show");
      void toast.offsetWidth;
      toastMessage.textContent = message;
      if (type === "success") {
        toast.classList.add("success");
        toastIcon.textContent = "✓";
      } else {
        toast.classList.add("error");
        toastIcon.textContent = "!";
      }
      toast.classList.add("show");
      toastTimer = setTimeout(() => toast.classList.remove("show"), 3500);
    }

    function initials(text) {
      if (!text) return "G";
      const parts = text.trim().split(/\s+/);
      if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
      return (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
    }

    function colorFromString(str) {
      const colors = [
        "linear-gradient(135deg, #0a4b6e, #0b6e4f)",
        "linear-gradient(135deg, #1668a8, #16a97a)",
        "linear-gradient(135deg, #0b6e4f, #1668a8)",
        "linear-gradient(135deg, #0a4b6e, #16a97a)"
      ];
      let hash = 0;
      for (let i = 0; i < str.length; i++) hash = str.charCodeAt(i) + ((hash << 5) - hash);
      return colors[Math.abs(hash) % colors.length];
    }

    function formatTime(iso) {
      if (!iso) return "";
      const d = new Date(iso);
      if (isNaN(d)) return iso;
      const diff = Date.now() - d.getTime();
      if (diff < 60000) return "just now";
      if (diff < 3600000) return Math.floor(diff / 60000) + "m ago";
      if (diff < 86400000) return Math.floor(diff / 3600000) + "h ago";
      if (diff < 604800000) return Math.floor(diff / 86400000) + "d ago";
      return d.toLocaleDateString();
    }

    function escapeHtml(str) {
      return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
    }

    function nowSql() {
      return new Date().toISOString().slice(0, 19).replace("T", " ");
    }

    async function api(payload) {
      const body = new URLSearchParams();
      Object.entries(payload).forEach(([k, v]) => body.append(k, v ?? ""));
      const res = await fetch(API, { method: "POST", body });
      const text = await res.text();
      try { return JSON.parse(text); } catch (e) { return { raw: text }; }
    }

    async function apiFilter(table, where) {
      const hasWhere = where && Object.keys(where).length > 0;
      const data = await api(
        hasWhere
          ? { type: "filter", table: table, where: JSON.stringify(where) }
          : { type: "get",    table: table }
      );
      return Array.isArray(data) ? data : [];
    }

    async function apiDelete(table, where) {
      return await api({
        type: "delete",
        table: table,
        where: JSON.stringify(where || {})
      });
    }

    function extractId(response) {
      if (!response) return null;
      if (response.id) return response.id;
      if (response.insert_id) return response.insert_id;
      if (response.insertId) return response.insertId;
      if (response.data && typeof response.data === "object") {
        if (response.data.id) return response.data.id;
        if (response.data.insert_id) return response.data.insert_id;
      }
      return null;
    }

    function saveLocal() {
      try { localStorage.setItem("tutorlink_groups_v9", JSON.stringify(groups)); } catch (e) {}
    }

    function loadLocal() {
      try {
        const raw = localStorage.getItem("tutorlink_groups_v9");
        return raw ? JSON.parse(raw) : [];
      } catch (e) { return []; }
    }

    async function loadGroups() {
      const [remoteGroups, remoteMembers] = await Promise.all([
        apiFilter(T.groups, {}),
        apiFilter(T.members, {})
      ]);

      if (remoteGroups.length > 0) {
        const membersByGroup = {};
        remoteMembers.forEach(m => {
          const gid = String(m.group_id);
          if (!membersByGroup[gid]) membersByGroup[gid] = [];
          membersByGroup[gid].push({
            name: m.fullname || m.name || m.email,
            email: m.email,
            role: m.role || "Member"
          });
        });

        groups = remoteGroups.map(g => ({
          id: g.id,
          name: g.name,
          topic: g.topic,
          description: g.description,
          owner_name: g.owner_name,
          owner_email: g.owner_email,
          created_at: g.created_at,
          members: membersByGroup[String(g.id)] || [],
          messages: [],
          resources: []
        }));
        saveLocal();
      } else {
        groups = loadLocal();
      }

      renderList("");
    }

    function isOwnerOf(g) {
      return (g.members || []).some(m => m.email === CURRENT_USER.email && m.role === "Owner");
    }

    function isAdminOf(g) {
      return (g.members || []).some(m =>
        m.email === CURRENT_USER.email && (m.role === "Owner" || m.role === "Admin")
      );
    }

    function isMemberOf(g) {
      return (g.members || []).some(m => m.email === CURRENT_USER.email);
    }

    function renderList(filter) {
      const term = (filter || "").trim().toLowerCase();
      const visible = groups.filter(g => {
        if (!term) return true;
        const hay = ((g.name || "") + " " + (g.topic || "") + " " + (g.description || "")).toLowerCase();
        return hay.includes(term);
      });

      listScroll.innerHTML = "";
      groupCountLabel.textContent = groups.length + (groups.length === 1 ? " group" : " groups");

      if (visible.length === 0) {
        const empty = document.createElement("div");
        empty.className = "empty-list";
        if (groups.length === 0) {
          empty.innerHTML = '<i class="fa-solid fa-users"></i>No study groups yet.<br>Be the first to <strong>create one</strong>.';
        } else {
          empty.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i>No group found for <strong>' + escapeHtml(term) + '</strong>';
        }
        listScroll.appendChild(empty);
        return;
      }

      visible.forEach(g => {
        const row = document.createElement("div");
        row.className = "group-row" + (String(g.id) === String(activeGroupId) ? " active" : "");
        row.dataset.id = g.id;

        const memberCount = (g.members && g.members.length) || 0;

        const avatar = document.createElement("div");
        avatar.className = "group-avatar";
        avatar.style.background = colorFromString(g.name);
        avatar.textContent = initials(g.name);

        const body = document.createElement("div");
        body.className = "group-body";

        const top = document.createElement("div");
        top.className = "group-top";

        const name = document.createElement("div");
        name.className = "group-name";
        name.textContent = g.name;

        const count = document.createElement("div");
        count.className = "group-count";
        count.innerHTML = '<i class="fa-solid fa-user-group"></i>' + memberCount;

        top.appendChild(name);
        top.appendChild(count);

        const topic = document.createElement("div");
        topic.className = "group-topic";
        topic.textContent = g.topic;

        body.appendChild(top);
        body.appendChild(topic);

        row.appendChild(avatar);
        row.appendChild(body);

        row.addEventListener("click", () => openGroup(g.id));
        listScroll.appendChild(row);
      });
    }

    async function openGroup(id) {
      const g = groups.find(x => String(x.id) === String(id));
      if (!g) return;

      activeGroupId = g.id;

      const [remoteMembers, allMessages, remoteResources] = await Promise.all([
        apiFilter(T.members, { group_id: g.id }),
        apiFilter(T.messages, {}),
        apiFilter(T.resources, { group_id: g.id })
      ]);

      const remoteMessages = allMessages.filter(m => String(m.group_id) === String(g.id));

      g.members = remoteMembers.map(m => ({
        name: m.fullname || m.name || m.email,
        email: m.email,
        role: m.role || "Member"
      }));

      g.messages = remoteMessages
        .map(m => ({
          id: m.id,
          name: m.name,
          email: m.email,
          text: m.text,
          sent: m.sent,
          type: m.type || "message"
        }))
        .sort((a, b) => new Date(a.sent) - new Date(b.sent));

      g.resources = remoteResources.map(r => ({
        id: r.id,
        url: r.url,
        by: r.added_by || r.by || "Member",
        email: r.email,
        sent: r.sent
      }));

      document.querySelectorAll(".group-row").forEach(r => {
        r.classList.toggle("active", String(r.dataset.id) === String(g.id));
      });

      detailEmpty.style.display = "none";
      tabs.style.display = "flex";

      detailAvatar.style.display = "flex";
      detailAvatar.textContent = initials(g.name);
      detailAvatar.style.background = colorFromString(g.name);

      detailTitle.textContent = g.name;
      detailSub.textContent = g.topic + " · " + g.members.length + " member" + (g.members.length === 1 ? "" : "s");

      const isMember = isMemberOf(g);
      chatInputBar.style.display = isMember ? "flex" : "none";
      resourceAddBar.style.display = isMember ? "flex" : "none";
      chatInput.disabled = !isMember;
      chatSend.disabled = !isMember;
      resourceInput.disabled = !isMember;
      resourceAddBtn.disabled = !isMember;

      renderHeaderActions(g);
      renderAnnouncementBar(g);
      renderTabs();
      startPolling();
      saveLocal();

      if (window.innerWidth <= 800) {
        wrap.classList.add("mobile-view-detail");
      }
    }

    function renderHeaderActions(g) {
      const old = document.querySelector(".header-actions");
      if (old) old.remove();

      const actions = document.createElement("div");
      actions.className = "header-actions";

      const isMember = isMemberOf(g);
      const isOwner = isOwnerOf(g);

      const joinBtn = document.createElement("button");
      joinBtn.className = "detail-action" + (isMember ? " joined" : "");
      joinBtn.innerHTML = isMember
        ? '<i class="fa-solid fa-check"></i> Joined'
        : '<i class="fa-solid fa-right-to-bracket"></i> Join';
      joinBtn.addEventListener("click", async () => {
        const idx = g.members.findIndex(m => m.email === CURRENT_USER.email);
        if (idx >= 0) {
          g.members.splice(idx, 1);
          saveLocal();
          await apiDelete(T.members, { group_id: g.id, email: CURRENT_USER.email });
          showToast("You left the group.", "success");
        } else {
          g.members.push({ name: CURRENT_USER.name, email: CURRENT_USER.email, role: "Member" });
          saveLocal();
          await api({
            type: "insert_member",
            group_id: g.id,
            fullname: CURRENT_USER.name,
            email: CURRENT_USER.email,
            role: "Member"
          });
          showToast("You joined the group!", "success");
        }
        const nowMember = isMemberOf(g);
        chatInputBar.style.display = nowMember ? "flex" : "none";
        resourceAddBar.style.display = nowMember ? "flex" : "none";
        chatInput.disabled = !nowMember;
        chatSend.disabled = !nowMember;
        resourceInput.disabled = !nowMember;
        resourceAddBtn.disabled = !nowMember;
        renderList(searchInput.value);
        renderHeaderActions(g);
        renderTabs();
      });
      actions.appendChild(joinBtn);

      if (isOwner) {
        const editBtn = document.createElement("button");
        editBtn.className = "header-icon-btn";
        editBtn.title = "Edit group";
        editBtn.innerHTML = '<i class="fa-solid fa-pen"></i>';
        editBtn.addEventListener("click", () => openEditModal(g));
        actions.appendChild(editBtn);

        const delBtn = document.createElement("button");
        delBtn.className = "header-icon-btn danger";
        delBtn.title = "Delete group";
        delBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
        delBtn.addEventListener("click", () => openDeleteModal(g));
        actions.appendChild(delBtn);
      }

      document.querySelector(".detail-header").appendChild(actions);
    }

    function renderAnnouncementBar(g) {
      const old = document.querySelector(".announcement-bar");
      if (old) old.remove();

      if (!isOwnerOf(g)) return;

      const latest = (g.messages || [])
        .filter(m => m.type === "announcement")
        .sort((a, b) => new Date(b.sent) - new Date(a.sent))[0];

      const bar = document.createElement("div");
      bar.className = "announcement-bar";

      const input = document.createElement("input");
      input.type = "text";
      input.placeholder = "Pin an announcement for the group...";
      input.value = latest ? latest.text : "";

      const btn = document.createElement("button");
      btn.innerHTML = '<i class="fa-solid fa-thumbtack"></i> Pin';

      btn.addEventListener("click", async () => {
        const text = input.value.trim();
        if (!text) {
          showToast("Type an announcement first.", "error");
          return;
        }

        await api({
          type: "insert_message",
          group_id: g.id,
          name: CURRENT_USER.name,
          email: CURRENT_USER.email,
          text: text,
          sent: nowSql(),
          msg_type: "announcement"
        });

        input.value = "";
        showToast("Announcement pinned.", "success");
      });

      bar.appendChild(input);
      bar.appendChild(btn);
      tabs.parentNode.insertBefore(bar, tabs);
    }

    tabButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        activeTab = btn.dataset.tab;
        renderTabs();
      });
    });

    function renderTabs() {
      const g = groups.find(x => String(x.id) === String(activeGroupId));
      if (!g) return;

      tabButtons.forEach(b => b.classList.toggle("active", b.dataset.tab === activeTab));
      tabChat.classList.toggle("active", activeTab === "chat");
      tabResources.classList.toggle("active", activeTab === "resources");
      tabMembers.classList.toggle("active", activeTab === "members");

      resourcesCount.textContent = (g.resources && g.resources.length) || 0;
      membersCount.textContent = (g.members && g.members.length) || 0;

      if (activeTab === "chat") renderChat(g);
      if (activeTab === "resources") renderResources(g);
      if (activeTab === "members") renderMembers(g);
    }

    function renderChat(g) {
      chatScroll.innerHTML = "";

      const messages = (g.messages || []).filter(m => !m.type || m.type === "message");
      const announcement = (g.messages || [])
        .filter(m => m.type === "announcement")
        .sort((a, b) => new Date(b.sent) - new Date(a.sent))[0];

      if (announcement && announcement.text) {
        const ann = document.createElement("div");
        ann.className = "chat-announcement";
        ann.innerHTML =
          '<div class="chat-announcement-label"><i class="fa-solid fa-bullhorn"></i>Announcement</div>' +
          '<div class="chat-announcement-text">' + escapeHtml(announcement.text) + '</div>';
        chatScroll.appendChild(ann);
      }

      if (messages.length === 0) {
        const empty = document.createElement("div");
        empty.className = "chat-empty";
        empty.innerHTML = '<i class="fa-regular fa-comments"></i><strong>No messages yet</strong>Be the first to send a message.';
        chatScroll.appendChild(empty);
        return;
      }

      messages.forEach(m => {
        const msg = document.createElement("div");
        msg.className = "msg" + (m.email === CURRENT_USER.email ? " own" : "");

        const av = document.createElement("div");
        av.className = "msg-avatar";
        av.style.background = colorFromString(m.name || m.email);
        av.textContent = initials(m.name || m.email);

        const bubble = document.createElement("div");
        bubble.className = "msg-bubble";

        const meta = document.createElement("div");
        meta.className = "msg-meta";

        const nm = document.createElement("div");
        nm.className = "msg-name";
        nm.textContent = m.email === CURRENT_USER.email ? "You" : (m.name || m.email);

        const tm = document.createElement("div");
        tm.className = "msg-time";
        tm.textContent = formatTime(m.sent);

        meta.appendChild(nm);
        meta.appendChild(tm);

        const text = document.createElement("div");
        text.className = "msg-text";
        text.textContent = m.text;

        bubble.appendChild(meta);
        bubble.appendChild(text);
        msg.appendChild(av);
        msg.appendChild(bubble);
        chatScroll.appendChild(msg);
      });

      chatScroll.scrollTop = chatScroll.scrollHeight;
    }

    async function sendMessage() {
      const g = groups.find(x => String(x.id) === String(activeGroupId));
      if (!g) return;

      const text = chatInput.value.trim();
      if (!text) return;

      if (!isMemberOf(g)) {
        showToast("Join the group to send messages.", "error");
        return;
      }

      const optimistic = {
        id: "tmp_" + Date.now(),
        name: CURRENT_USER.name,
        email: CURRENT_USER.email,
        text: text,
        sent: new Date().toISOString(),
        type: "message"
      };

      if (!g.messages) g.messages = [];
      g.messages.push(optimistic);
      chatInput.value = "";
      renderChat(g);

      await api({
        type: "insert_message",
        group_id: g.id,
        name: CURRENT_USER.name,
        email: CURRENT_USER.email,
        text: text,
        sent: nowSql()
      });

      saveLocal();
    }

    chatSend.addEventListener("click", sendMessage);
    chatInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });

    function startPolling() {
      if (pollTimer) clearInterval(pollTimer);
      pollTimer = setInterval(pollActiveGroup, POLL_MS);
    }

    async function pollActiveGroup() {
      if (!activeGroupId) return;
      const g = groups.find(x => String(x.id) === String(activeGroupId));
      if (!g) return;

      const [allMessages, remoteMembers, remoteResources] = await Promise.all([
        apiFilter(T.messages, {}),
        apiFilter(T.members, { group_id: g.id }),
        apiFilter(T.resources, { group_id: g.id })
      ]);

      const remoteMessages = allMessages.filter(m => String(m.group_id) === String(g.id));

      g.messages = remoteMessages
        .map(m => ({
          id: m.id,
          name: m.name,
          email: m.email,
          text: m.text,
          sent: m.sent,
          type: m.type || "message"
        }))
        .sort((a, b) => new Date(a.sent) - new Date(b.sent));

      g.members = remoteMembers.map(m => ({
        name: m.fullname || m.name || m.email,
        email: m.email,
        role: m.role || "Member"
      }));

      g.resources = remoteResources.map(r => ({
        id: r.id,
        url: r.url,
        by: r.added_by || r.by || "Member",
        email: r.email,
        sent: r.sent
      }));

      const isMember = isMemberOf(g);
      chatInputBar.style.display = isMember ? "flex" : "none";
      resourceAddBar.style.display = isMember ? "flex" : "none";
      chatInput.disabled = !isMember;
      chatSend.disabled = !isMember;
      resourceInput.disabled = !isMember;
      resourceAddBtn.disabled = !isMember;

      if (activeTab === "chat") renderChat(g);
      if (activeTab === "members") renderMembers(g);
      if (activeTab === "resources") renderResources(g);

      membersCount.textContent = g.members.length;
      resourcesCount.textContent = g.resources.length;

      renderList(searchInput.value);
      renderHeaderActions(g);
      renderAnnouncementBar(g);
      saveLocal();
    }

    function renderResources(g) {
      resourceList.innerHTML = "";
      const list = g.resources || [];
      resourcesCount.textContent = list.length;

      if (list.length === 0) {
        const empty = document.createElement("div");
        empty.className = "empty-list";
        empty.style.padding = "2rem 0";
        empty.innerHTML = '<i class="fa-solid fa-link"></i>No resources shared yet.<br>Add the first link above.';
        resourceList.appendChild(empty);
        return;
      }

      list.forEach(r => {
        const li = document.createElement("li");
        li.className = "resource-item";

        const ic = document.createElement("div");
        ic.className = "resource-icon";
        ic.innerHTML = '<i class="fa-solid fa-link"></i>';

        const info = document.createElement("div");
        info.className = "resource-info";

        const a = document.createElement("a");
        a.className = "resource-link";
        a.href = r.url;
        a.target = "_blank";
        a.rel = "noopener noreferrer";
        a.textContent = r.url;

        const meta = document.createElement("div");
        meta.className = "resource-meta";
        meta.textContent = "Added by " + r.by + " · " + formatTime(r.sent);

        info.appendChild(a);
        info.appendChild(meta);

        const del = document.createElement("button");
        del.className = "resource-delete";
        del.title = "Remove";
        del.innerHTML = '<i class="fa-solid fa-trash"></i>';
        del.addEventListener("click", async () => {
          if (!isMemberOf(g)) {
            showToast("Join the group to manage resources.", "error");
            return;
          }
          g.resources = g.resources.filter(x => String(x.id) !== String(r.id));
          saveLocal();
          renderResources(g);
          await apiDelete(T.resources, { id: r.id });
          showToast("Resource removed.", "success");
        });

        li.appendChild(ic);
        li.appendChild(info);
        li.appendChild(del);
        resourceList.appendChild(li);
      });
    }

    resourceAddBtn.addEventListener("click", async () => {
      const g = groups.find(x => String(x.id) === String(activeGroupId));
      if (!g) return;

      if (!isMemberOf(g)) {
        showToast("Join the group to add resources.", "error");
        return;
      }

      const url = resourceInput.value.trim();
      if (!url) return;
      if (!/^https?:\/\//i.test(url)) {
        showToast("Please enter a valid http(s) link.", "error");
        return;
      }

      const resource = {
        id: "r_" + Date.now(),
        url: url,
        by: CURRENT_USER.name,
        email: CURRENT_USER.email,
        sent: new Date().toISOString()
      };

      if (!g.resources) g.resources = [];
      g.resources.push(resource);
      resourceInput.value = "";
      saveLocal();
      renderResources(g);
      showToast("Resource added.", "success");

      await api({
        type: "insert_resource",
        group_id: g.id,
        url: url,
        added_by: CURRENT_USER.name,
        email: CURRENT_USER.email,
        sent: nowSql()
      });
    });

    function renderMembers(g) {
      memberList.innerHTML = "";
      const list = g.members || [];
      membersCount.textContent = list.length;

      const isOwner = isOwnerOf(g);

      list.forEach(m => {
        const li = document.createElement("li");
        li.className = "member-item";

        const av = document.createElement("div");
        av.className = "member-avatar";
        av.style.background = colorFromString(m.name || m.email);
        av.textContent = initials(m.name || m.email);

        const nm = document.createElement("div");
        nm.className = "member-name";
        nm.textContent = m.email === CURRENT_USER.email ? (m.name || m.email) + " (you)" : (m.name || m.email);

        const rl = document.createElement("div");
        rl.className = "member-role" + (m.role === "Owner" ? " owner" : m.role === "Admin" ? " admin" : "");
        rl.textContent = m.role;

        li.appendChild(av);
        li.appendChild(nm);
        li.appendChild(rl);

        const actions = document.createElement("div");
        actions.className = "member-actions";

        if (isOwner && m.role !== "Owner" && m.email !== CURRENT_USER.email) {
          const promoteBtn = document.createElement("button");
          promoteBtn.className = "member-action-btn";
          promoteBtn.title = m.role === "Admin" ? "Demote to Member" : "Promote to Admin";
          promoteBtn.innerHTML = m.role === "Admin"
            ? '<i class="fa-solid fa-arrow-down"></i>'
            : '<i class="fa-solid fa-arrow-up"></i>';
          promoteBtn.addEventListener("click", async () => {
            const newRole = m.role === "Admin" ? "Member" : "Admin";
            m.role = newRole;
            saveLocal();
            renderMembers(g);
            await api({
              type: "update_member",
              group_id: g.id,
              email: m.email,
              role: newRole
            });
            showToast((m.name || m.email) + " is now " + newRole + ".", "success");
          });
          actions.appendChild(promoteBtn);

          const rm = document.createElement("button");
          rm.className = "member-action-btn danger";
          rm.title = "Remove member";
          rm.innerHTML = '<i class="fa-solid fa-user-minus"></i>';
          rm.addEventListener("click", async () => {
            g.members = g.members.filter(x => x.email !== m.email);
            saveLocal();
            renderMembers(g);
            renderList(searchInput.value);
            await apiDelete(T.members, { group_id: g.id, email: m.email });
            showToast((m.name || m.email) + " removed.", "success");
          });
          actions.appendChild(rm);
        }

        if (actions.children.length > 0) li.appendChild(actions);

        memberList.appendChild(li);
      });
    }

    function openCreateModal() {
      editingGroupId = null;
      groupModalTitle.textContent = "Create study group";
      groupModalSub.textContent = "Start a community around a topic you're learning.";
      groupModalIcon.className = "modal-icon";
      groupModalIcon.innerHTML = '<i class="fa-solid fa-users"></i>';
      confirmGroupModal.innerHTML = '<i class="fa-solid fa-plus"></i> Create group';
      groupNameInput.value = "";
      groupTopicInput.value = "";
      groupDescriptionInput.value = "";
      [groupNameInput, groupTopicInput, groupDescriptionInput].forEach(i => i.classList.remove("error"));
      [groupNameError, groupTopicError, groupDescriptionError].forEach(e => e.classList.remove("show"));
      groupModal.classList.add("show");
      setTimeout(() => groupNameInput.focus(), 50);
    }

    function openEditModal(g) {
      editingGroupId = g.id;
      groupModalTitle.textContent = "Edit group";
      groupModalSub.textContent = "Update the group details.";
      groupModalIcon.className = "modal-icon";
      groupModalIcon.innerHTML = '<i class="fa-solid fa-pen"></i>';
      confirmGroupModal.innerHTML = '<i class="fa-solid fa-check"></i> Save changes';
      groupNameInput.value = g.name;
      groupTopicInput.value = g.topic;
      groupDescriptionInput.value = g.description;
      [groupNameInput, groupTopicInput, groupDescriptionInput].forEach(i => i.classList.remove("error"));
      [groupNameError, groupTopicError, groupDescriptionError].forEach(e => e.classList.remove("show"));
      groupModal.classList.add("show");
      setTimeout(() => groupNameInput.focus(), 50);
    }

    function closeGroupModal() {
      groupModal.classList.remove("show");
      editingGroupId = null;
    }

    createGroupBtn.addEventListener("click", openCreateModal);
    cancelGroupModal.addEventListener("click", closeGroupModal);

    groupModal.addEventListener("click", (e) => {
      if (e.target === groupModal) closeGroupModal();
    });

    function validateGroupModal() {
      let valid = true;
      [groupNameInput, groupTopicInput, groupDescriptionInput].forEach(i => i.classList.remove("error"));
      [groupNameError, groupTopicError, groupDescriptionError].forEach(e => e.classList.remove("show"));

      if (!groupNameInput.value.trim()) {
        groupNameInput.classList.add("error");
        groupNameError.classList.add("show");
        valid = false;
      }
      if (!groupTopicInput.value.trim()) {
        groupTopicInput.classList.add("error");
        groupTopicError.classList.add("show");
        valid = false;
      }
      if (!groupDescriptionInput.value.trim()) {
        groupDescriptionInput.classList.add("error");
        groupDescriptionError.classList.add("show");
        valid = false;
      }
      return valid;
    }

    confirmGroupModal.addEventListener("click", async () => {
      if (!validateGroupModal()) return;

      const name = groupNameInput.value.trim();
      const topic = groupTopicInput.value.trim();
      const description = groupDescriptionInput.value.trim();

      if (editingGroupId) {
        const g = groups.find(x => String(x.id) === String(editingGroupId));
        if (g) {
          g.name = name;
          g.topic = topic;
          g.description = description;
        }

        await api({
          type: "update_group",
          id: editingGroupId,
          name: name,
          topic: topic,
          description: description
        });

        closeGroupModal();
        renderList(searchInput.value);
        const activeG = groups.find(x => String(x.id) === String(activeGroupId));
        if (activeG && String(activeGroupId) === String(editingGroupId)) {
          detailTitle.textContent = name;
          detailSub.textContent = topic + " · " + activeG.members.length + " members";
          detailAvatar.textContent = initials(name);
          detailAvatar.style.background = colorFromString(name);
        }
        saveLocal();
        showToast("Group updated.", "success");
        return;
      }

      const insertRes = await api({
        type: "insert_group",
        name: name,
        topic: topic,
        description: description,
        owner_name: CURRENT_USER.name,
        owner_email: CURRENT_USER.email
      });

      let realGroupId = extractId(insertRes);

      if (!realGroupId) {
        const found = await apiFilter(T.groups, { owner_email: CURRENT_USER.email });
        if (Array.isArray(found) && found.length > 0) {
          found.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
          realGroupId = found[0].id;
        }
      }

      if (!realGroupId) {
        realGroupId = "g_" + Date.now();
      }

      const newGroup = {
        id: realGroupId,
        name: name,
        topic: topic,
        description: description,
        owner_name: CURRENT_USER.name,
        owner_email: CURRENT_USER.email,
        members: [{ name: CURRENT_USER.name, email: CURRENT_USER.email, role: "Owner" }],
        messages: [],
        resources: []
      };

      groups.unshift(newGroup);
      saveLocal();
      closeGroupModal();
      renderList(searchInput.value);
      showToast("Study group created!", "success");

      await api({
        type: "insert_member",
        group_id: realGroupId,
        fullname: CURRENT_USER.name,
        email: CURRENT_USER.email,
        role: "Owner"
      });

      openGroup(realGroupId);
    });

    [groupNameInput, groupTopicInput, groupDescriptionInput].forEach((input, i) => {
      input.addEventListener("input", () => {
        const errs = [groupNameError, groupTopicError, groupDescriptionError];
        if (input.classList.contains("error") && input.value.trim()) {
          input.classList.remove("error");
          errs[i].classList.remove("show");
        }
      });
    });

    function openDeleteModal(g) {
      deletingGroupId = g.id;
      deleteModal.classList.add("show");
    }

    function closeDeleteModal() {
      deleteModal.classList.remove("show");
      deletingGroupId = null;
    }

    cancelDeleteModal.addEventListener("click", closeDeleteModal);

    deleteModal.addEventListener("click", (e) => {
      if (e.target === deleteModal) closeDeleteModal();
    });

    confirmDeleteModal.addEventListener("click", async () => {
      if (!deletingGroupId) return;

      await api({ type: "delete_group", id: deletingGroupId });
      await apiDelete(T.members, { group_id: deletingGroupId });
      await apiDelete(T.messages, { group_id: deletingGroupId });
      await apiDelete(T.resources, { group_id: deletingGroupId });

      groups = groups.filter(g => String(g.id) !== String(deletingGroupId));

      if (String(activeGroupId) === String(deletingGroupId)) {
        activeGroupId = null;
        detailEmpty.style.display = "flex";
        tabs.style.display = "none";
        const ann = document.querySelector(".announcement-bar");
        if (ann) ann.remove();
        const actions = document.querySelector(".header-actions");
        if (actions) actions.remove();
        detailAvatar.style.display = "none";
        detailTitle.textContent = "Select a group";
        detailSub.textContent = "Choose one from the list to open it";
      }

      saveLocal();
      renderList(searchInput.value);
      closeDeleteModal();
      showToast("Group deleted.", "success");
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        if (groupModal.classList.contains("show")) closeGroupModal();
        if (deleteModal.classList.contains("show")) closeDeleteModal();
      }
    });

    searchInput.addEventListener("input", () => renderList(searchInput.value));

    detailBack.addEventListener("click", () => {
      wrap.classList.remove("mobile-view-detail");
    });

    loadGroups();
  </script>
</body>

</html>