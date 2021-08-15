<ul id="nav" class="nav-dropdown nav-dropdown-horizontal">
    <li class="dir">Projects
        <ul>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=projects.manage">Manage Projects</a></li>
            <?
                $sql = "select id, title from projects order by title";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    ?>
                    <li class="dir">Generate Plots
                        <ul>
                            <?
                                while ($rs = mysql_fetch_array($result)) {
                                    ?>
                                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=projects.generate_plots&project_id=<?= $rs["id"] ?>"><?= $rs["title"] ?></a></li>
                                    <?
                                }
                            ?>
                        </ul>
                    </li>
                    <?
                }
            ?>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=plots.manage">Manage Plots</a></li>
        </ul>
    </li>
    <li class="menu_separator"></li>
    <li class="dir">Accounts
        <ul>
            <li class="dir">Invoices
                <ul>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=invoices.add.wizard">Create New</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=invoices.manage">Manage Invoices</a></li>
                </ul>
            </li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=accounts.manage">Manage Accounts</a></li>
            <li class="dir">Employees
                <ul>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=employees.add">Create New</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=employees.manage">Manage Employees</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="menu_separator"></li>
    <li class="dir">Customers
        <ul>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=customers.add">Create Customer</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=customers.manage">Manage Customers</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=nominees.add">Create Nominee</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=nominees.manage">Manage Nominees</a></li>
        </ul>
    </li>
    <li class="menu_separator"></li>
    <li class="dir">Modules
        <ul>
            <li class="dir">Dealers
                <ul>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=dealers.add">Create Dealer</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=dealers.manage">Manage Dealers</a></li>
                </ul>
            </li>
            <li class="dir">Land Owner
                <ul>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=landowner.add">Create Land Owner</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=landowner.manage">Manage Land Owner</a></li>
                </ul>
            </li>
            <li class="dir">Investor
                <ul>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=investor.add">Create Investor</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=investor.manage">Manage Investor</a></li>
                </ul>
            </li>
            <li class="dir">Partner
                <ul>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=partner.add">Create Partner</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=partner.manage">Manage Partner</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="menu_separator"></li>
    <li class="dir">Reports
        <ul>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.projects.statement">Project Statement</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.plots.statement">Plot Statement</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.payments_auth">Payments Authorization</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.receipts_handover">Receipts Handover</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.cheques_clearance">Cheques / Cash Clearance</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.customers_list">Customers List</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.customers_plots_list">Customers Plots List</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.dealer.commission">Dealer Commission</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.income_expense">Income / Expense</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.due_installments">Due Installments</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=rpt.petty_expense">Petty Expense</a></li>
        </ul>
    </li>
    <li class="menu_separator"></li>
    <li class="dir">System
        <ul>
            <li class="dir">Users
                <ul>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=users.add">Create New</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=users.manage">Manage</a></li>
                </ul>
            </li>
            <li class="dir">Lookup Data
                <ul>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=lookup.banks">Banks</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=lookup.cities">Cities</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=lookup.countries">Countries</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=lookup.plot_features">Plot Features</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=lookup.head_office_percentage">Head Office %age</a></li>
                    <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=lookup.deposit_accounts">Deposit Accounts</a></li>
                </ul>
            </li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=users.chngprofilepasswd">Change Password</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=users.tasks">My Tasks</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=users.assigned_tasks">Assigned Tasks</a></li>
            <li><a href="index.php?ss=<?PHP print $ss; ?>&amp;mod=database_backup">Database Backup</a></li>
        </ul>
    </li>
</ul>