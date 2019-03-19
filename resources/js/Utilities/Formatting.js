import Moment from "moment";
import _ from "lodash";

export default class Formatting {
    /**
     * @param {string|Object} dateOrDateString in event format YYYY-MM-DD HH:mm or Moment Date Object
     * @returns {*}
     */
    static relativeDate(dateOrDateString) {
        let date = dateOrDateString;

        if (typeof dateOrDateString === "string")
            date = Formatting.parseEventDate(dateOrDateString);

        let length = Moment.duration(date.diff(Moment()));
        if (length.years() > 0) {
            return date.format('HH:mm DD/MM/YY');
        } else if (length.months() > 0 || length.weeks() > 0) {
            return date.format('HH:mm Do MMM');
        } else if (length.days() > 0) {
            return date.format('HH:mm dddd');
        }  else if (length.days() === 0) {
            return 'today ' + date.format('HH:mm');
        }

        return date.format('HH:mm DD/MM/YY');
    }

    /**
     * @param {string} date in event format YYYY-MM-DD HH:mm
     * @returns {*}
     */
    static parseEventDate(date) {
        return Moment(date, 'YYYY-MM-DD HH:mm');
    }

    static sentenceCase(theString) {
        return theString.toLowerCase().replace(/(^\s*\w|[\.\!\?]\s*\w)/g,(c) => c.toUpperCase());
    }

    static titleCase(theString) {
        return _.startCase(theString);
    }

    static fullGender(genderShortcut) {
        if (genderShortcut === 1 || genderShortcut === 'M')
            return "Mele";
        else if (genderShortcut === 0 || genderShortcut === 'F') {
            return "Female";
        } else
            return "Another"
    }
}