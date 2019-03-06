export default class EventsMockup {
    static get randomEvent() {
        return {
            // miejsce, opis, datę i godzinę, czas trwania, maksymalną liczbę gości, datę końca zbierania zgłoszeń,
            name: "Great event-name!",
            description: "Lorem ipsum dolor sit amet, zril perfecto eum ut, scaevola repudiandae eu eam, has ipsum pertinacia an. Possim contentiones cum ei. Tempor liberavisse sit in, pro eu aeterno denique concludaturque. No nam viris quaestio, no sit vitae patrioque. Ne cum prima decore ignota. Porro constituto et his, no laoreet perfecto vim.",
            address: {
                street: "Testowa 1",
                city: "Warsaw",
                zipCode: "00-107"
            },
            startingAt: "12:30",
            dateFrom: "21-12-2021",
            dateTo: "22-12-2022",
            maxGuests: 99,
            openTo: "20-12-2020",
        }
    }
}