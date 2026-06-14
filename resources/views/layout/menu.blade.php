<div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">

        {{-- TRANSACTION --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('transactions*') ? 'active' : '' }}"
                href="{{ route('transactions.index') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        fill="{{ Request::is('transactions*') ? 'white' : '#67748e' }}"
                        viewBox="0 0 16 16">
                        <path d="M1.92.506a.5.5 0 0 1 .434.14L6 4.293V1.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v2.793l3.646-3.647a.5.5 0 0 1 .708.708L10.707 5H14.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-2.793l3.647 3.646a.5.5 0 0 1-.708.708L11 9.707V14.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-4.793l-3.646 3.647a.5.5 0 0 1-.708-.708L6.293 9H1.5a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5h4.793L2.646 1.354a.5.5 0 0 1-.726-.848z"/>
                        <path d="M0 10.5A1.5 1.5 0 0 1 1.5 9h1A1.5 1.5 0 0 1 4 10.5v1A1.5 1.5 0 0 1 2.5 13h-1A1.5 1.5 0 0 1 0 11.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z"/>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Transaction</span>
            </a>
        </li>

        {{-- LOCATION --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('locations*') ? 'active' : '' }}"
                href="{{ route('locations.index') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        fill="{{ Request::is('locations*') ? 'white' : '#67748e' }}"
                        viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Location</span>
            </a>
        </li>

        {{-- VEHICLE TYPE --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('vehicle-types*') ? 'active' : '' }}"
                href="{{ route('vehicle-types.index') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        fill="{{ Request::is('vehicle-types*') ? 'white' : '#67748e' }}"
                        viewBox="0 0 16 16">
                        <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679c.033.161.049.325.049.49v.413C16 9.78 15.1 10.5 14 10.5H2c-1.1 0-2-.72-2-1.889v-.413c0-.165.016-.329.049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.807.807 0 0 0 .381-.404l.792-1.848ZM4.82 3a1.5 1.5 0 0 0-1.379.91l-.792 1.847a1.8 1.8 0 0 1-.853.904.807.807 0 0 0-.43.564L1.03 8.904a1.636 1.636 0 0 1-.03.294H14.97a1.636 1.636 0 0 1-.03-.294l-.335-1.68a.807.807 0 0 0-.43-.563 1.807 1.807 0 0 1-.853-.904l-.792-1.848A1.5 1.5 0 0 0 11.18 3H4.82Z"/>
                        <path d="M1 10v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1H1Zm11 0v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h-3ZM6 8a1 1 0 0 0-1 1v.5a.5.5 0 0 0 1 0V9h4v.5a.5.5 0 0 0 1 0V9a1 1 0 0 0-1-1H6Z"/>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Vehicle Type</span>
            </a>
        </li>

    </ul>
</div>
