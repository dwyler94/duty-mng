<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <router-link to="/home" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt blue"></i>
          <p>
            打刻
          </p>
        </router-link>
      </li>

      <li class="nav-item">
        <router-link to="/ownAttend" class="nav-link">
          <i class="nav-icon fas fa-list orange"></i>
          <p>
            勤怠状況-申請
          </p>
        </router-link>
      </li>

      @can('isAdmin')
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-cog green"></i>
          <p>
            マスタ管理
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <router-link to="/master" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                事務所マスタ
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/users" class="nav-link">
              <i class="nav-icon fas fa-tags green"></i>
              <p>
                社員マスタ
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/master" class="nav-link">
              <i class="nav-icon fas fa-cogs white"></i>
              <p>
                時間帯マスタ
              </p>
            </router-link>
            <router-link to="/master" class="nav-link">
              <i class="nav-icon fas fa-cogs white"></i>
              <p>
                休暇マスタ
              </p>
            </router-link>
            <router-link to="/master" class="nav-link">
              <i class="nav-icon fas fa-cogs white"></i>
              <p>
                所定労働時間マスタ
              </p>
            </router-link>
          </li>
        </ul>
      </li>

      @endcan
      
      

      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off red"></i>
          <p>
            {{ __('Logout') }}
          </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>