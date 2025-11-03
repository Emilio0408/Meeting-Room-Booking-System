<?php

namespace Src\Utils;
use DateTime;
use DateTimeZone;
use Src\DAO\Booking;


class Utils
{

    public static function checkParametersForBooking(string $TimeSlot, string $data): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) { //Se la data non è nel formato giusto
            return false;
        }

        // 2. Validazione formato orario (HH:00)
        if (!preg_match('/^(0[9]|1[0-9]):00$/', $TimeSlot)) { //Se l'ora non è nel formato HH:00
            return false;
        }

        $bookedData = DateTime::createFromFormat('Y-m-d', $data);
        if (!$bookedData) { //Se la data non è valida (ad esempio viene inserito un mese inesistente)
            return false;
        }
        $bookedData->setTime(0, 0, 0); // Reset ore per confronto con la data attuale

        // Data e ora attuali
        $currentDay = new DateTime('now', new DateTimeZone('Europe/Rome'));
        $currentData = (clone $currentDay)->setTime(0, 0, 0);

        if ($bookedData < $currentData) { //Controlliamo se la data prenotata sia minore di quella di oggi, in tal caso si sta cercando di fare una prenotazione al passato e quindi blocchiamo
            return false;
        }

        // 5. Controllo che la data sia entro 7 giorni
        $maxData = (clone $currentData)->modify('+7 days');
        if ($bookedData > $maxData) {
            return false;
        }

        // 6. Controllo orari validi (9:00 - 19:00)
        $validTimeSlots = [
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00',
            '18:00',
            '19:00'
        ];

        if (!in_array($TimeSlot, $validTimeSlots)) { //Se la fascia oraria richiesta non rientra tra quelle supportate
            return false;
        }

        // 7. Se la data richiesta per la prenotazione è quella corrente, controlliamo che l'orario richiesto sia nel futuro
        if ($bookedData == $currentData) {
            $currentHour = (int) $currentDay->format('H');
            $BookedHour = (int) substr($TimeSlot, 0, 2);

            // Se siamo nelle 13:xx, la fascia 13:00 non è più prenotabile
            if ($BookedHour <= $currentHour) {
                return false;
            }
        }


        return true;

    }


    public static function extractAvailableTimeSlots(string $data, array $bookingOnSelectedRoom): array
    {
        $AllTimeSlots = [
            '9:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00',
            '18:00',
            '19:00'
        ];

        $notAvailableSlots = [];

        //Se la data richiesta è quella attuale, rimuoviamo tutte le fasce orarie precedenti a quella attuale
        $currentData = new DateTime('now', new DateTimeZone('Europe/Rome'));
        $currentData = $currentData->setTime(0, 0, 0); //Impostiamo a 0 per il confronto
        $selectedData = DateTime::createFromFormat('Y-m-d', $data, new DateTimeZone('Europe/Rome'));
        $selectedData = $selectedData->setTime(0, 0, 0); //Impostiamo a 0 per il confronto


        if ($currentData == $selectedData) {
            $currentData = new DateTime('now', new DateTimeZone('Europe/Rome')); //Reimpostiamo l'orario attuale
            $currentHour = $currentData->format('H:00');
            $notAvailableSlots = array_diff($AllTimeSlots, array_slice($AllTimeSlots, array_search($currentHour, $AllTimeSlots) + 1));
        }

        //Prendiamo tutte le fasce orarie già prenotate sulla data selezionata e le inseriamo tra gli slot non disponibili
        foreach ($bookingOnSelectedRoom as $booking) {
            $bookingData = DateTime::createFromFormat('Y-m-d', $booking['DATA']);
            $requestedData = DateTime::createFromFormat('Y-m-d', $data);

            if ($bookingData == $requestedData)
                $notAvailableSlots[] = substr($booking['FasciaOraria'], 0, -3);
        }

        $availableSlots = array_values(array_diff($AllTimeSlots, $notAvailableSlots));

        return $availableSlots;
    }




    public static function checkBookingOwnership($bookingOfUser, Booking $booking): bool
    {

        foreach ($bookingOfUser as $b) {
            if ($b['DATA'] == $booking->getData() && substr($b['FasciaOraria'], 0, -3) == $booking->getTimeslot() && $b['IDSala'] == $booking->getIDRoom())
                return true;

        }

        return false;
    }


    public static function filterTodayBookings($bookings): array
    {
        $today = new DateTime('now', new DateTimeZone('Europe/Rome'));
        $today->setTime(0, 0, 0);

        $bookingOfToday = [];

        foreach ($bookings as $booking) {
            $dataOfBooking = DateTime::createFromFormat('Y-m-d', $booking['DATA'], new DateTimeZone('Europe/Rome'));
            $dataOfBooking->setTime(0, 0, 0);

            if ($today == $dataOfBooking)
                $bookingOfToday[] = $booking;
        }

        return $bookingOfToday;
    }
}


?>