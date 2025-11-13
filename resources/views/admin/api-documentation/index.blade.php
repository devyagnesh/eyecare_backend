@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    /* Ultra Modern API Documentation Design */
    :root {
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --gradient-info: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --primary: #667eea;
        --primary-dark: #5568d3;
        --primary-light: #8b9aff;
        --bg: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-tertiary: #f1f5f9;
        --text: #0f172a;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --border: #e2e8f0;
        --border-light: #f1f5f9;
        --code-bg: #0f172a;
        --code-text: #e2e8f0;
        --success: #10b981;
        --error: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] {
        --bg: #0f172a;
        --bg-secondary: #1e293b;
        --bg-tertiary: #334155;
        --text: #f1f5f9;
        --text-secondary: #cbd5e1;
        --text-muted: #94a3b8;
        --border: #334155;
        --border-light: #475569;
        --code-bg: #020617;
        --code-text: #cbd5e1;
    }

    * {
        scroll-behavior: smooth;
    }

    .api-docs-wrapper {
        background: var(--bg);
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow-x: hidden;
    }

    /* Decorative Background Elements */
    .api-docs-wrapper::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 400px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        pointer-events: none;
        z-index: 0;
    }

    /* Premium Top Navigation */
    .docs-header {
        position: sticky;
        top: 0;
        z-index: 300;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border);
        padding: 1.25rem 2.5rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }

    [data-theme="dark"] .docs-header {
        background: rgba(15, 23, 42, 0.95);
    }

    .docs-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        max-width: 1800px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .docs-brand-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .docs-brand {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        font-size: 1.375rem;
        font-weight: 800;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-decoration: none;
        letter-spacing: -0.02em;
    }

    .docs-brand-icon {
        width: 40px;
        height: 40px;
        background: var(--gradient-primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: var(--shadow-md);
    }

    .docs-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .docs-meta-badge {
        padding: 0.375rem 0.875rem;
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 20px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    /* Enhanced Search */
    .docs-search-section {
        flex: 1;
        max-width: 700px;
        position: relative;
    }

    .docs-search-container {
        position: relative;
    }

    .docs-search-input {
        width: 100%;
        padding: 0.875rem 1.25rem 0.875rem 3.5rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        background: var(--bg-secondary);
        color: var(--text);
        font-size: 0.9375rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-xs);
    }

    .docs-search-input:focus {
        outline: none;
        border-color: var(--primary);
        background: var(--bg);
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), var(--shadow-md);
        transform: translateY(-1px);
    }

    .docs-search-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1.125rem;
        z-index: 1;
    }

    .docs-search-clear {
        position: absolute;
        right: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0.5rem;
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .docs-search-clear:hover {
        background: var(--border);
        color: var(--text);
    }

    .docs-search-clear.visible {
        display: flex;
    }

    .keyboard-hint {
        position: absolute;
        right: 3.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        color: var(--text-muted);
        background: var(--bg);
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        border: 1px solid var(--border);
        font-family: 'Monaco', 'Menlo', monospace;
    }

    /* Filter Pills */
    .docs-filters {
        display: flex;
        gap: 0.625rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .filter-pill {
        padding: 0.5rem 1rem;
        background: var(--bg-secondary);
        border: 1.5px solid var(--border);
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .filter-pill::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: var(--gradient-primary);
        transition: left 0.3s ease;
        z-index: 0;
    }

    .filter-pill:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .filter-pill.active {
        background: var(--gradient-primary);
        border-color: transparent;
        color: white;
        box-shadow: var(--shadow-md);
    }

    .filter-pill.active::before {
        left: 0;
    }

    .filter-pill span {
        position: relative;
        z-index: 1;
    }

    .filter-pill i {
        position: relative;
        z-index: 1;
        font-size: 0.875rem;
    }

    .docs-search-stats {
        margin-top: 0.75rem;
        font-size: 0.875rem;
        color: var(--text-muted);
        display: none;
    }

    .docs-search-stats.visible {
        display: block;
    }

    /* Main Layout */
    .docs-body {
        display: flex;
        flex: 1;
        max-width: 1800px;
        margin: 0 auto;
        width: 100%;
        position: relative;
        z-index: 1;
    }

    /* Modern Sidebar */
    .docs-sidebar {
        width: 300px;
        background: var(--bg-secondary);
        border-right: 1px solid var(--border);
        padding: 2rem 0;
        position: sticky;
        top: 81px;
        height: calc(100vh - 281px);
        overflow-y: auto;
        overflow-x: hidden;
    }

    .docs-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .docs-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .docs-sidebar::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    .docs-sidebar::-webkit-scrollbar-thumb:hover {
        background: var(--text-muted);
    }

    .sidebar-section {
        margin-bottom: 2.5rem;
        padding: 0 1.5rem;
    }

    .sidebar-section-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border);
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav-item {
        margin: 0.25rem 0;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.75rem 1.25rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.9375rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 3px solid transparent;
        border-radius: 0 8px 8px 0;
        margin-left: -1.5rem;
        padding-left: 1.5rem;
        position: relative;
    }

    .sidebar-nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--gradient-primary);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .sidebar-nav-link:hover {
        background: rgba(102, 126, 234, 0.08);
        color: var(--primary);
        transform: translateX(4px);
    }

    .sidebar-nav-link.active {
        background: rgba(102, 126, 234, 0.12);
        color: var(--primary);
        font-weight: 700;
    }

    .sidebar-nav-link.active::before {
        transform: scaleY(1);
    }

    .sidebar-nav-link i {
        font-size: 1.125rem;
        width: 20px;
        text-align: center;
    }

    /* Content Area */
    .docs-content {
        flex: 1;
        padding: 3rem 4rem;
        max-width: 1000px;
    }

    /* Hero Section */
    .docs-hero {
        margin-bottom: 4rem;
        padding: 3rem 0;
        text-align: center;
    }

    .docs-hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 1.5rem 0;
        line-height: 1.1;
        letter-spacing: -0.03em;
    }

    .docs-hero-description {
        font-size: 1.25rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.7;
        max-width: 700px;
        margin: 0 auto;
    }

    /* Group Header */
    .docs-group {
        margin-bottom: 5rem;
        scroll-margin-top: 100px;
    }

    .docs-group-header {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 3px solid var(--border);
        position: relative;
    }

    .docs-group-header::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100px;
        height: 3px;
        background: var(--gradient-primary);
        border-radius: 3px;
    }

    .docs-group-title {
        font-size: 2.75rem;
        font-weight: 900;
        color: var(--text);
        margin: 0 0 1rem 0;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .docs-group-description {
        font-size: 1.125rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.8;
    }

    /* Premium Endpoint Cards */
    .endpoint-card {
        background: var(--bg);
        border: 2px solid var(--border);
        border-radius: 16px;
        padding: 2.5rem;
        margin-bottom: 3rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        scroll-margin-top: 100px;
        position: relative;
        overflow: hidden;
    }

    .endpoint-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .endpoint-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-xl);
        transform: translateY(-4px);
    }

    .endpoint-card:hover::before {
        transform: scaleX(1);
    }

    .endpoint-header {
        margin-bottom: 2rem;
    }

    .endpoint-method-url {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .method-badge {
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        min-width: 80px;
        text-align: center;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }

    .method-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.1;
        background: white;
    }

    .method-get { 
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .method-post { 
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    .method-put { 
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    .method-delete { 
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    .method-patch { 
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .endpoint-url {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Courier New', monospace;
        font-size: 1rem;
        color: var(--primary);
        font-weight: 600;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.625rem 1.125rem;
        border-radius: 8px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        transition: all 0.3s;
    }

    .endpoint-url:hover {
        background: rgba(102, 126, 234, 0.15);
        border-color: var(--primary);
    }

    .endpoint-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text);
        margin: 1rem 0 0.75rem 0;
        line-height: 1.3;
        letter-spacing: -0.01em;
    }

    .endpoint-description {
        font-size: 1.0625rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.8;
    }

    /* Section Headers */
    .docs-section {
        margin: 3rem 0;
    }

    .docs-section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 1.5rem 0;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
    }

    .docs-section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--gradient-primary);
    }

    .docs-section-title i {
        color: var(--primary);
        font-size: 1.375rem;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 8px;
    }

    /* Premium Tables */
    .docs-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 2rem 0;
        font-size: 0.9375rem;
        background: var(--bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
    }

    .docs-table th {
        background: var(--bg-secondary);
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 700;
        color: var(--text);
        border-bottom: 2px solid var(--border);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .docs-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text);
        vertical-align: top;
    }

    .docs-table tr:last-child td {
        border-bottom: none;
    }

    .docs-table tbody tr {
        transition: all 0.2s;
    }

    .docs-table tbody tr:hover {
        background: var(--bg-secondary);
        transform: scale(1.01);
    }

    .param-code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Courier New', monospace;
        color: var(--primary);
        font-weight: 700;
        font-size: 0.875rem;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }

    .type-badge {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Courier New', monospace;
        color: var(--success);
        background: rgba(16, 185, 129, 0.1);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .required-tag {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
        color: #dc2626;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .optional-tag {
        background: var(--bg-secondary);
        color: var(--text-muted);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid var(--border);
    }

    /* Ultra Premium Code Blocks */
    .code-block-wrapper {
        position: relative;
        margin: 2rem 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        border: 2px solid var(--border);
        background: var(--code-bg);
    }

    .code-block-header {
        background: rgba(15, 23, 42, 0.8);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    [data-theme="dark"] .code-block-header {
        background: rgba(2, 6, 23, 0.8);
    }

    .code-block-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--code-text);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .code-block-label::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--success);
        border-radius: 50%;
        box-shadow: 0 0 8px var(--success);
    }

    .code-copy-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1.5px solid rgba(255, 255, 255, 0.2);
        color: var(--code-text);
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .code-copy-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .code-copy-btn.copied {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .code-copy-btn i {
        font-size: 1rem;
    }

    .code-block-content {
        background: var(--code-bg);
        padding: 2rem;
        overflow-x: auto;
    }

    .code-block-content pre {
        margin: 0;
        color: var(--code-text);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Courier New', monospace;
        font-size: 0.9375rem;
        line-height: 1.8;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .code-block-content code {
        color: var(--code-text);
    }

    /* Status Codes Grid */
    .status-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
        margin: 2rem 0;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.25rem;
        background: var(--bg-secondary);
        border-radius: 12px;
        border-left: 4px solid;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-sm);
    }

    .status-item:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .status-code-badge {
        font-weight: 800;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: 0.9375rem;
        min-width: 70px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .status-2xx { 
        background: rgba(16, 185, 129, 0.1);
        border-left-color: var(--success);
    }
    .status-2xx .status-code-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .status-4xx { 
        background: rgba(239, 68, 68, 0.1);
        border-left-color: var(--error);
    }
    .status-4xx .status-code-badge {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    .status-5xx { 
        background: rgba(245, 158, 11, 0.1);
        border-left-color: var(--warning);
    }
    .status-5xx .status-code-badge {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    /* Callout Boxes */
    .callout {
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin: 2.5rem 0;
        border-left: 4px solid;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }

    .callout::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.05;
        background: var(--gradient-primary);
    }

    .callout-info {
        background: rgba(102, 126, 234, 0.08);
        border-left-color: var(--primary);
        color: var(--text);
    }

    .callout-warning {
        background: rgba(245, 158, 11, 0.08);
        border-left-color: var(--warning);
        color: var(--text);
    }

    .callout p {
        margin: 0;
        font-size: 1rem;
        line-height: 1.8;
        position: relative;
        z-index: 1;
    }

    .callout strong {
        color: var(--text);
        font-weight: 700;
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--text-muted);
    }

    .no-results-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: var(--bg-secondary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: var(--text-muted);
        opacity: 0.5;
    }

    .no-results h3 {
        font-size: 1.75rem;
        margin: 0 0 0.75rem 0;
        color: var(--text);
        font-weight: 800;
    }

    .no-results p {
        font-size: 1.0625rem;
        color: var(--text-secondary);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .docs-content {
            padding: 2.5rem 3rem;
        }
    }

    @media (max-width: 1024px) {
        .docs-body {
            flex-direction: column;
        }

        .docs-sidebar {
            width: 100%;
            height: auto;
            position: relative;
            top: 0;
            border-right: none;
            border-bottom: 1px solid var(--border);
        }

        .docs-content {
            padding: 2rem 1.5rem;
        }

        .docs-header-content {
            flex-direction: column;
            gap: 1.5rem;
        }

        .docs-search-section {
            max-width: 100%;
        }

        .docs-hero-title {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">API Documentation</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">API Documentation</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-list">
        <button class="btn btn-primary-light btn-wave" id="theme-toggle">
            <i class="ri-moon-line" id="theme-icon"></i>
            <span id="theme-text">Dark Mode</span>
        </button>
        <a href="{{ route('admin.api-documentation.download') }}" class="btn btn-primary btn-wave">
            <i class="ri-download-line align-middle"></i> Download Postman Collection
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="api-docs-wrapper" id="api-docs-wrapper">
    <!-- Premium Header -->
    <header class="docs-header">
        <div class="docs-header-content">
            <div class="docs-brand-section">
                <a href="#getting-started" class="docs-brand">
                    <div class="docs-brand-icon">
                        <i class="ri-code-s-slash-line"></i>
                    </div>
                    <span>API Reference</span>
                </a>
                <div class="docs-meta">
                    <span class="docs-meta-badge">v{{ $apiVersion }}</span>
                    <span>{{ $lastUpdated }}</span>
                </div>
            </div>
            
            <div class="docs-search-section">
                <div class="docs-search-container">
                    <i class="ri-search-line docs-search-icon"></i>
                    <input type="text" 
                           class="docs-search-input" 
                           id="api-search" 
                           placeholder="Search endpoints, methods, URLs... (Press / to focus)"
                           autocomplete="off">
                    <button class="docs-search-clear" id="search-clear" title="Clear search">
                        <i class="ri-close-line"></i>
                    </button>
                    <span class="keyboard-hint">/</span>
                </div>
                
                <div class="docs-filters" id="search-filters">
                    <button class="filter-pill active" data-filter="all">
                        <i class="ri-list-check"></i>
                        <span>All</span>
                    </button>
                    <button class="filter-pill" data-filter="get">
                        <i class="ri-download-line"></i>
                        <span>GET</span>
                    </button>
                    <button class="filter-pill" data-filter="post">
                        <i class="ri-upload-line"></i>
                        <span>POST</span>
                    </button>
                    <button class="filter-pill" data-filter="put">
                        <i class="ri-edit-line"></i>
                        <span>PUT</span>
                    </button>
                    <button class="filter-pill" data-filter="delete">
                        <i class="ri-delete-bin-line"></i>
                        <span>DELETE</span>
                    </button>
                </div>
                
                <div class="docs-search-stats" id="search-results">
                    <strong id="results-count">0</strong> endpoint(s) found
                </div>
            </div>
        </div>
    </header>

    <!-- Main Body -->
    <div class="docs-body">
        <!-- Sidebar -->
        <aside class="docs-sidebar">
            <div class="sidebar-section">
                <div class="sidebar-section-title">API Groups</div>
                <ul class="sidebar-nav" id="sidebar-nav">
                    @foreach($endpoints as $groupIndex => $group)
                    <li class="sidebar-nav-item">
                        <a href="#group-{{ $groupIndex }}" 
                           class="sidebar-nav-link {{ $groupIndex === 0 ? 'active' : '' }}"
                           data-group="group-{{ $groupIndex }}">
                            <i class="ri-{{ $group['icon'] }}-line"></i>
                            <span>{{ $group['group'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <!-- Content -->
        <main class="docs-content">
            <!-- Hero Section -->
            <div class="docs-hero" id="getting-started">
                <h1 class="docs-hero-title">API Documentation</h1>
                <p class="docs-hero-description">
                    Comprehensive guide to integrate with our RESTful API. Explore endpoints, understand request formats, and build amazing applications.
                </p>
            </div>

            <!-- Getting Started Info -->
            <div class="callout callout-info">
                <p><strong>Base URL:</strong> <code style="background: rgba(102, 126, 234, 0.2); padding: 4px 8px; border-radius: 4px; font-family: monospace; font-weight: 600;">{{ $baseUrl }}</code></p>
                <p style="margin-top: 0.75rem;"><strong>Authentication:</strong> Most endpoints require authentication. Include your Bearer token in the Authorization header.</p>
                <p style="margin-top: 0.5rem;"><strong>Rate Limiting:</strong> API requests are rate-limited. Check response headers for rate limit information.</p>
            </div>

            <!-- API Groups -->
            @foreach($endpoints as $groupIndex => $group)
            <div class="docs-group" id="group-{{ $groupIndex }}" data-group-name="{{ strtolower($group['group']) }}">
                <div class="docs-group-header">
                    <h1 class="docs-group-title">{{ $group['group'] }}</h1>
                    <p class="docs-group-description">{{ $group['description'] }}</p>
                </div>

                @foreach($group['endpoints'] as $endpointIndex => $endpoint)
                <article class="endpoint-card" 
                         id="endpoint-{{ $groupIndex }}-{{ $endpointIndex }}"
                         data-endpoint-name="{{ strtolower($endpoint['name']) }}"
                         data-endpoint-method="{{ strtolower($endpoint['method']) }}"
                         data-endpoint-url="{{ strtolower($endpoint['url']) }}"
                         data-endpoint-description="{{ strtolower($endpoint['description']) }}">
                    
                    <div class="endpoint-header">
                        <div class="endpoint-method-url">
                            <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                {{ $endpoint['method'] }}
                            </span>
                            <code class="endpoint-url">{{ $endpoint['url'] }}</code>
                        </div>
                        <h2 class="endpoint-title">{{ $endpoint['name'] }}</h2>
                        <p class="endpoint-description">{{ $endpoint['description'] ?: 'No description available.' }}</p>
                    </div>

                    <!-- Headers -->
                    @if(!empty($endpoint['headers']))
                    <div class="docs-section">
                        <h3 class="docs-section-title">
                            <i class="ri-file-list-3-line"></i>
                            Headers
                        </h3>
                        <table class="docs-table">
                            <thead>
                                <tr>
                                    <th>Header</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Description</th>
                                    <th>Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($endpoint['headers'] as $headerName => $headerData)
                                <tr>
                                    <td><code class="param-code">{{ $headerName }}</code></td>
                                    <td><span class="type-badge">{{ $headerData['type'] ?? 'string' }}</span></td>
                                    <td>
                                        @if($headerData['required'] ?? false)
                                        <span class="required-tag">Required</span>
                                        @else
                                        <span class="optional-tag">Optional</span>
                                        @endif
                                    </td>
                                    <td>{{ $headerData['description'] ?? '' }}</td>
                                    <td><code style="font-size: 0.8125rem; background: var(--bg-secondary); padding: 4px 8px; border-radius: 4px; font-weight: 600;">{{ $headerData['example'] ?? '' }}</code></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Query Parameters -->
                    @if(!empty($endpoint['query_parameters']))
                    <div class="docs-section">
                        <h3 class="docs-section-title">
                            <i class="ri-search-line"></i>
                            Query Parameters
                        </h3>
                        <table class="docs-table">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($endpoint['query_parameters'] as $paramName => $paramDetails)
                                <tr>
                                    <td><code class="param-code">{{ $paramName }}</code></td>
                                    <td><span class="type-badge">{{ $paramDetails['type'] ?? 'string' }}</span></td>
                                    <td>
                                        @if($paramDetails['required'] ?? false)
                                        <span class="required-tag">Required</span>
                                        @else
                                        <span class="optional-tag">Optional</span>
                                        @endif
                                    </td>
                                    <td>{{ $paramDetails['description'] ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Request Parameters -->
                    @if(!empty($endpoint['parameters']))
                    <div class="docs-section">
                        <h3 class="docs-section-title">
                            <i class="ri-file-edit-line"></i>
                            Request Parameters
                        </h3>
                        <table class="docs-table">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($endpoint['parameters'] as $paramName => $paramDetails)
                                <tr>
                                    <td><code class="param-code">{{ $paramName }}</code></td>
                                    <td><span class="type-badge">{{ $paramDetails['type'] ?? 'string' }}</span></td>
                                    <td>
                                        @if($paramDetails['required'] ?? false)
                                        <span class="required-tag">Required</span>
                                        @else
                                        <span class="optional-tag">Optional</span>
                                        @endif
                                    </td>
                                    <td>{{ $paramDetails['description'] ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Request Payload -->
                    @if($endpoint['payload_example'])
                    <div class="docs-section">
                        <h3 class="docs-section-title">
                            <i class="ri-code-s-slash-line"></i>
                            Request Payload
                        </h3>
                        <div class="code-block-wrapper">
                            <div class="code-block-header">
                                <span class="code-block-label">JSON</span>
                                <button class="code-copy-btn" onclick="copyCode('payload-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i>
                                    <span>Copy</span>
                                </button>
                            </div>
                            <div class="code-block-content">
                                <pre id="payload-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['payload_example']) ? $endpoint['payload_example'] : json_encode($endpoint['payload_example'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Success Response -->
                    @if($endpoint['success_response'])
                    <div class="docs-section">
                        <h3 class="docs-section-title" style="color: var(--success);">
                            <i class="ri-checkbox-circle-line"></i>
                            Success Response
                        </h3>
                        <div class="code-block-wrapper">
                            <div class="code-block-header">
                                <span class="code-block-label">JSON</span>
                                <button class="code-copy-btn" onclick="copyCode('success-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i>
                                    <span>Copy</span>
                                </button>
                            </div>
                            <div class="code-block-content">
                                <pre id="success-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['success_response']) ? $endpoint['success_response'] : json_encode($endpoint['success_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Error Response -->
                    @if($endpoint['error_response'])
                    <div class="docs-section">
                        <h3 class="docs-section-title" style="color: var(--error);">
                            <i class="ri-error-warning-line"></i>
                            Error Response
                        </h3>
                        <div class="code-block-wrapper">
                            <div class="code-block-header">
                                <span class="code-block-label">JSON</span>
                                <button class="code-copy-btn" onclick="copyCode('error-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i>
                                    <span>Copy</span>
                                </button>
                            </div>
                            <div class="code-block-content">
                                <pre id="error-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['error_response']) ? $endpoint['error_response'] : json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Status Codes -->
                    @if(!empty($endpoint['status_codes']))
                    <div class="docs-section">
                        <h3 class="docs-section-title">
                            <i class="ri-information-line"></i>
                            Status Codes
                        </h3>
                        <div class="status-list">
                            @foreach($endpoint['status_codes'] as $code => $message)
                            <div class="status-item status-{{ floor($code / 100) }}xx">
                                <span class="status-code-badge">{{ $code }}</span>
                                <span>{{ $message }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if(!empty($endpoint['notes']))
                    <div class="callout callout-warning">
                        <p><strong>Note:</strong> {{ $endpoint['notes'] }}</p>
                    </div>
                    @endif
                </article>
                @endforeach
            </div>
            @endforeach

            <!-- No Results -->
            <div id="no-results" class="no-results" style="display: none;">
                <div class="no-results-icon">
                    <i class="ri-search-line"></i>
                </div>
                <h3>No endpoints found</h3>
                <p>Try adjusting your search criteria or filters</p>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function($) {
    'use strict';

    const wrapper = document.getElementById('api-docs-wrapper');
    const searchInput = document.getElementById('api-search');
    const searchClear = document.getElementById('search-clear');
    const searchStats = document.getElementById('search-results');
    const resultsCount = document.getElementById('results-count');
    const noResults = document.getElementById('no-results');
    const filterPills = document.querySelectorAll('.filter-pill');
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');

    let currentFilter = 'all';
    let searchTerm = '';

    // Theme Toggle
    const savedTheme = localStorage.getItem('api-docs-theme') || 'light';
    if (savedTheme === 'dark') {
        wrapper.setAttribute('data-theme', 'dark');
        themeIcon.className = 'ri-sun-line';
        themeText.textContent = 'Light Mode';
    }

    themeToggle.addEventListener('click', function() {
        const currentTheme = wrapper.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            wrapper.removeAttribute('data-theme');
            themeIcon.className = 'ri-moon-line';
            themeText.textContent = 'Dark Mode';
            localStorage.setItem('api-docs-theme', 'light');
        } else {
            wrapper.setAttribute('data-theme', 'dark');
            themeIcon.className = 'ri-sun-line';
            themeText.textContent = 'Light Mode';
            localStorage.setItem('api-docs-theme', 'dark');
        }
    });

    // Keyboard Shortcut
    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
        
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            performSearch();
            searchInput.blur();
        }
    });

    // Filter Pills
    filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            performSearch();
        });
    });

    // Search Input
    searchInput.addEventListener('input', function() {
        searchTerm = this.value.toLowerCase().trim();
        searchClear.classList.toggle('visible', searchTerm.length > 0);
        performSearch();
    });

    // Clear Search
    searchClear.addEventListener('click', function() {
        searchInput.value = '';
        searchTerm = '';
        this.classList.remove('visible');
        performSearch();
        searchInput.focus();
    });

    // Enhanced Search
    function performSearch() {
        const term = searchTerm;
        const filter = currentFilter;
        let visibleCount = 0;

        if (term === '' && filter === 'all') {
            $('.endpoint-card, .docs-group').show();
            searchStats.classList.remove('visible');
            noResults.hide();
            updateActiveSidebar();
            return;
        }

        $('.endpoint-card').each(function() {
            const $card = $(this);
            const name = $card.data('endpoint-name') || '';
            const method = $card.data('endpoint-method') || '';
            const url = $card.data('endpoint-url') || '';
            const description = $card.data('endpoint-description') || '';

            const matchesSearch = term === '' || 
                name.includes(term) || 
                method.includes(term) || 
                url.includes(term) || 
                description.includes(term);

            const matchesFilter = filter === 'all' || method === filter;

            if (matchesSearch && matchesFilter) {
                $card.show();
                $card.closest('.docs-group').show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });

        $('.docs-group').each(function() {
            const $group = $(this);
            const visibleInGroup = $group.find('.endpoint-card:visible').length;
            if (visibleInGroup === 0 && (term !== '' || filter !== 'all')) {
                $group.hide();
            }
        });

        resultsCount.textContent = visibleCount;
        searchStats.classList.toggle('visible', term !== '' || filter !== 'all');

        if (visibleCount === 0) {
            noResults.show();
        } else {
            noResults.hide();
            updateActiveSidebar();
        }
    }

    // Update Active Sidebar
    function updateActiveSidebar() {
        const groups = document.querySelectorAll('.docs-group');
        const sidebarLinks = document.querySelectorAll('.sidebar-nav-link');
        
        let activeGroup = null;
        groups.forEach((group, index) => {
            const rect = group.getBoundingClientRect();
            if (rect.top <= 150 && rect.bottom >= 150 && $(group).is(':visible')) {
                activeGroup = index;
            }
        });

        sidebarLinks.forEach((link, index) => {
            link.classList.toggle('active', index === activeGroup);
        });
    }

    // Sidebar Navigation
    document.querySelectorAll('.sidebar-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                document.querySelectorAll('.sidebar-nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Scroll Spy
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(updateActiveSidebar, 100);
    });

    // Enhanced Copy Function with SweetAlert2
    window.copyCode = function(elementId, button) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const text = element.textContent || element.innerText;
        
        navigator.clipboard.writeText(text).then(function() {
            const $btn = $(button);
            const originalHtml = $btn.html();
            $btn.html('<i class="ri-check-line"></i><span>Copied!</span>');
            $btn.addClass('copied');
            
            setTimeout(function() {
                $btn.html(originalHtml);
                $btn.removeClass('copied');
            }, 2000);

            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Copied to clipboard!',
                    text: 'Code has been copied successfully.'
                });
            }
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: 'Failed to copy!',
                    text: 'Please try again or copy manually.'
                });
            } else {
                alert('Failed to copy to clipboard. Please try again.');
            }
        });
    };

    // Initialize
    updateActiveSidebar();
    filterPills[0].classList.add('active');

})(jQuery);
</script>
@endpush
