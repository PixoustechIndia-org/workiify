<?php
class Services extends Controller {
    public function index() {
        $data = [
            'title' => 'Services - Workiify'
        ];
        $this->view('pages/services', $data);
    }

    public function hot_desk() {
        $data = [
            'title' => 'Hot Desk - Workiify'
        ];
        $this->view('pages/services-hot-desk', $data);
    }

    public function dedicated_desk() {
        $data = [
            'title' => 'Dedicated Desk - Workiify'
        ];
        $this->view('pages/services-dedicated-desk', $data);
    }

    public function private_office() {
        $data = [
            'title' => 'Private Office - Workiify'
        ];
        $this->view('pages/services-private-office', $data);
    }

    public function meeting_room() {
        $data = [
            'title' => 'Meeting Room - Workiify'
        ];
        $this->view('pages/services-meeting-room', $data);
    }

    public function virtual_office() {
        $data = [
            'title' => 'Virtual Office - Workiify'
        ];
        $this->view('pages/services-virtual-office', $data);
    }

    public function day_pass() {
        $data = [
            'title' => 'Day Pass - Workiify'
        ];
        $this->view('pages/services-day-pass', $data);
    }

    public function event_spaces() {
        $data = [
            'title' => 'Event Spaces - Workiify'
        ];
        $this->view('pages/services-event-spaces', $data);
    }
}
