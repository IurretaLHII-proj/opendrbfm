-- UPDATE HINTS WITHOUT OPERATION --

UPDATE process_hint_type t,
(   SELECT
    h.id FROM
    process_hint_type AS h
    LEFT JOIN process_op AS o
        ON h.id = o.id
) t1
SET t.op_id = 163
WHERE t1.id = t.id;
