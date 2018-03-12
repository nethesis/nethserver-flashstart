# flashstart.rb

Facter.add('flashstart') do
    setcode do
        if File.exist?('/var/log/squid/access.log')
            file = File.open('/var/log/squid/access.log', 'rb')
            ips = []
            until file.eof?
                line = file.readline
                fields = line.strip.split("\s")
                next unless fields[3] =~ /TCP_HIT/
                t = Time.at(fields[0].to_i)
                now = Time.now - (24 * 60 * 60) # count since yesterday
                if now.mday == t.mday && now.month == t.month && now.year == t.year
                    ips.push(fields[2])
                end
            end
            ips.uniq.length
        end
    end
end
