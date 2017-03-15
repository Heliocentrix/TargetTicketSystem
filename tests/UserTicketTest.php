<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TargetInk\User;

class UserTicketTest extends TestCase
{
    public function testTicketCreation()
    {
        // $user = factory(TargetInk\User::class)->create();
        // $admin = factory(TargetInk\User::class, 'admin')->create();

        // $ticketName = 'Ticket Sample ' . rand(0, 9999);

        // // Run the tests
        // $this->visit('/')
        //      ->withSession([
        //         'captcha' => [
        //             'sensitive' => false,
        //             'key' => bcrypt('asdfgh')
        //         ]
        //      ])
        //      ->type($user->email, 'email')
        //      ->type('secret', 'password')
        //      ->type('asdfgh', 'captcha')
        //      ->press('Login')
        //      ->seePageIs('/')

        //      // Tickets
        //      ->visit($user->company_slug . '/tickets')
        //      ->see('There are no tickets to show')

        //      // Create a ticket
        //      ->click('CREATE A NEW TICKET')
        //      ->seePageIs($user->company_slug . '/tickets/create')
        //      ->see('CHOOSE A TICKET')
        //      ->type($ticketName, 'title')
        //      ->type('Test ticket', 'content')
        //      ->press('submit')
        //      ->see('SUBMISSION SUCCESS')
        //      ->visit($user->company_slug . '/tickets')
        //      ->see($ticketName)
        //      ->see('Web Amends')

        //      // Check archives
        //      ->visit($user->company_slug . '/tickets?archived=1')
        //      ->see('There are no tickets to show')

        //      // Add the ticket to archive
        //      ->visit($user->company_slug . '/tickets')
        //      ->click('archive-ticket')
        //      ->see('The ticket has been successfully archived')
        //      ->visit($user->company_slug . '/tickets?archived=1')
        //      ->see($ticketName)

        //      // Take it out of the archive
        //      ->click('unarchive-ticket')
        //      ->see('The ticket has been successfully unarchived')
        //      ->visit($user->company_slug . '/tickets')
        //      ->see($ticketName)

        //      // Get admin to reply
        //      ->click('Logout')
        //      ->visit('auth/login')
        //      ->withSession([
        //         'captcha' => [
        //             'sensitive' => false,
        //             'key' => bcrypt('asdfgh')
        //         ]
        //      ])
        //      ->type($admin->email, 'email')
        //      ->type('secret', 'password')
        //      ->type('asdfgh', 'captcha')
        //      ->press('Login')
        //      ->visit('maintenance')
        //      ->see($user->company)
        //      ->visit($user->company_slug . '/tickets')
        //      ->see($ticketName)
        //      ->click('goto-ticket')
        //      ->see('TICKET DETAILS')
        //      ->type('Sample reply from admin', 'content')
        //      ->press('Respond')
        //      ->see('The response has been sent')
        //      ->visit($user->company_slug . '/tickets')
        //      ->see('icon-response')

        //      // Get user to reply
        //      ->click('Logout')
        //      ->visit('auth/login')
        //      ->withSession([
        //         'captcha' => [
        //             'sensitive' => false,
        //             'key' => bcrypt('asdfgh')
        //         ]
        //      ])
        //      ->type($user->email, 'email')
        //      ->type('secret', 'password')
        //      ->type('asdfgh', 'captcha')
        //      ->press('Login')
        //      ->visit($user->company_slug . '/tickets')
        //      ->see('icon-response')
        //      ->click('goto-ticket')
        //      ->see('TICKET DETAILS')
        //      ->see('Sample reply from admin')
        //      ->type('Sample reply from user', 'content')

        //      // Get admin to archive
        //      ->click('Logout')
        //      ->visit('auth/login')
        //      ->withSession([
        //         'captcha' => [
        //             'sensitive' => false,
        //             'key' => bcrypt('asdfgh')
        //         ]
        //      ])
        //      ->type($admin->email, 'email')
        //      ->type('secret', 'password')
        //      ->type('asdfgh', 'captcha')
        //      ->press('Login')
        //      ->visit($user->company_slug . '/tickets?archived=1')
        //      ->see('There are no tickets to show')
        //      ->visit($user->company_slug . '/tickets')
        //      ->click('archive-ticket')
        //      ->see('The ticket has been successfully archived')
        //      ->visit($user->company_slug . '/tickets?archived=1')
        //      ->see($ticketName)
        //      ->click('unarchive-ticket')
        //      ->see('The ticket has been successfully unarchived')
        //      ->visit($user->company_slug . '/tickets')
        //      ->see($ticketName)

        //      // Get admin to delete
        //      ->press('delete-ticket')
        //      ->see('The ticket has been deleted')

        //      // Logout
        //      ->click('Logout')
        //      ->seePageIs('auth/login')
        //      ;

        // // Remove users from database
        // $user->delete();
        // $admin->delete();
    }

}
